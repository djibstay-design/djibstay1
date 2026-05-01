<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ReservationCreatedMail;
use App\Models\Reservation;
use App\Models\TypeChambre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Réservations liées au compte OU anciennes sans user_id mais même email (app avant correctif fillable)
        $reservations = Reservation::query()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere(function ($q2) use ($user) {
                        $q2->whereNull('user_id')
                            ->where('email_client', $user->email);
                    });
            })
            ->with('chambre.typeChambre.hotel.mainImage')
            ->orderByDesc('created_at')
            ->get();

        $data = $reservations->map(function (Reservation $r) {
            return $this->formatReservation($r);
        });

        return response()->json(['data' => $data]);
    }

    public function show(Reservation $reservation, Request $request): JsonResponse
    {
        if (! $this->userOwnsReservation($reservation, $request->user())) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $reservation->load('chambre.typeChambre.hotel.mainImage');

        return response()->json(['data' => $this->formatReservation($reservation)]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_id' => ['required', 'integer', 'exists:types_chambre,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1'],
            'special_requests' => ['nullable', 'string'],
            'photo_carte'  => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'photo_visage' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ]);

        $typeChambre = TypeChambre::with('chambres')->findOrFail($validated['room_id']);

        $chambre = $typeChambre->chambres()
            ->where('etat', 'DISPONIBLE')
            ->whereDoesntHave('reservations', function ($q) use ($validated) {
                $q->whereIn('statut', ['EN_ATTENTE', 'CONFIRMEE'])
                    ->where('date_debut', '<', $validated['check_out'])
                    ->where('date_fin', '>', $validated['check_in']);
            })
            ->first();

        if (! $chambre) {
            return response()->json([
                'message' => 'Aucune chambre disponible pour ces dates.',
            ], 422);
        }

        $user = $request->user();
        $nuits = (strtotime($validated['check_out']) - strtotime($validated['check_in'])) / 86400;
        $montantTotal = round($typeChambre->prix_par_nuit * $validated['guests'] * $nuits, 2);

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'chambre_id' => $chambre->id,
            'nom_client' => $user->name,
            'prenom_client' => $user->prenom ?? '',
            'email_client' => $user->email,
            'telephone_client' => $user->phone,
            'code_identite' => 'APP-'.$user->id,
            'date_reservation' => now()->toDateString(),
            'date_debut' => $validated['check_in'],
            'date_fin' => $validated['check_out'],
            'quantite' => $validated['guests'],
            'prix_unitaire' => $typeChambre->prix_par_nuit,
            'montant_total' => $montantTotal,
            'statut' => 'EN_ATTENTE',
            'code_reservation' => 'RES-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
        ]);

        $basePath = 'reservations/'.$reservation->id;
        $reservation->update([
            'photo_carte'  => $request->file('photo_carte')->store($basePath, 'public'),
            'photo_visage' => $request->file('photo_visage')->store($basePath, 'public'),
        ]);

        $reservation->load([
            'chambre.typeChambre.hotel.user',
            'chambre.typeChambre.hotel.admin',
            'chambre.typeChambre.hotel.mainImage',
        ]);

        return response()->json([
            'booking' => $this->formatReservation($reservation),
        ], 201);
    }

    /**
     * Même comportement que ReservationController (web) : email client + gestionnaire(s) de l'hôtel.
     */
    private function sendReservationEmails(Reservation $reservation): void
    {
        try {
            $hotel = $reservation->chambre?->typeChambre?->hotel;
            if (! $hotel) {
                return;
            }

            if ($reservation->email_client) {
                Mail::to($reservation->email_client)->send(new ReservationCreatedMail($reservation, forAdmin: false));
            }

            $recipients = [];
            if ($owner && $owner->email) $recipients[] = $owner->email;
            
            if ($hotel->admin_id && $hotel->admin_id != $hotel->user_id) {
                if ($hotel->admin && $hotel->admin->email) {
                    $recipients[] = $hotel->admin->email;
                }
            }
            $recipients = array_unique($recipients);

            if (!empty($recipients)) {
                $mail = Mail::to($recipients);
                $globalReceiver = \App\Models\SiteSetting::get('mail_resa_receiver');
                if ($globalReceiver && !in_array($globalReceiver, $recipients)) {
                    $mail->bcc($globalReceiver);
                }
                $mail->send(new ReservationCreatedMail($reservation, forAdmin: true));
            }
        } catch (\Throwable $e) {
            Log::error('API réservation : envoi des e-mails impossible', [
                'reservation_id' => $reservation->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Vérifier la disponibilité pour des dates données
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_type_id' => ['required', 'integer', 'exists:types_chambre,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
        ]);

        $typeChambre = TypeChambre::with(['hotel', 'chambres'])->findOrFail($validated['room_type_id']);

        // Compter les chambres disponibles pour le type donné
        $availableCount = 0;
        $unavailableCount = 0;

        foreach ($typeChambre->chambres as $chambre) {
            $isAvailable = $chambre->etat === 'DISPONIBLE'
                && ! $chambre->reservations()
                    ->whereIn('statut', ['EN_ATTENTE', 'CONFIRMEE'])
                    ->where('date_debut', '<', $validated['check_out'])
                    ->where('date_fin', '>', $validated['check_in'])
                    ->exists();

            if ($isAvailable) {
                $availableCount++;
            } else {
                $unavailableCount++;
            }
        }

        return response()->json([
            'available' => $availableCount > 0,
            'available_rooms' => $availableCount,
            'unavailable_rooms' => $unavailableCount,
            'total_rooms' => count($typeChambre->chambres),
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'hotel_info' => [
                'id' => $typeChambre->hotel->id,
                'name' => $typeChambre->hotel->nom,
            ],
        ]);
    }

    /**
     * Obtenir le calendrier de disponibilité pour une plage de dates
     */
    public function getAvailabilityCalendar(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_type_id' => ['required', 'integer', 'exists:types_chambre,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        $typeChambre = TypeChambre::with(['chambres.reservations'])->findOrFail($validated['room_type_id']);
        $startDate = new \DateTime($validated['start_date']);
        $endDate = new \DateTime($validated['end_date']);
        $totalRooms = count($typeChambre->chambres);

        $calendar = [];
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            
            // Compter les chambres disponibles pour cette date
            $availableCount = 0;
            
            foreach ($typeChambre->chambres as $chambre) {
                if ($chambre->isAvailableForDates($dateStr, (new \DateTime($dateStr))->modify('+1 day')->format('Y-m-d'))) {
                    $availableCount++;
                }
            }

            $calendar[$dateStr] = [
                'date' => $dateStr,
                'day_of_week' => $currentDate->format('l'),
                'available_rooms' => $availableCount,
                'total_rooms' => $totalRooms,
                'is_available' => $availableCount > 0,
                'occupancy_rate' => round((($totalRooms - $availableCount) / $totalRooms) * 100, 2),
            ];

            $currentDate->modify('+1 day');
        }

        return response()->json([
            'room_type' => [
                'id' => $typeChambre->id,
                'name' => $typeChambre->nom_type,
                'price_per_night' => $typeChambre->prix_par_nuit,
                'total_rooms' => $totalRooms,
            ],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'calendar' => $calendar,
        ]);
    }

    public function cancel(Reservation $reservation, Request $request): JsonResponse
    {
        if (! $this->userOwnsReservation($reservation, $request->user())) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        if ($reservation->statut === 'ANNULEE') {
            return response()->json(['message' => 'Réservation déjà annulée'], 422);
        }

        $reservation->update(['statut' => 'ANNULEE']);
        $reservation->load('chambre.typeChambre.hotel.mainImage');

        return response()->json([
            'booking' => $this->formatReservation($reservation),
        ]);
    }

    public function submitPayment(Request $request, Reservation $reservation): JsonResponse
    {
        if (!$this->userOwnsReservation($reservation, $request->user())) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        if ($reservation->hasPaidDeposit()) {
            return response()->json(['message' => 'L\'acompte est déjà payé'], 422);
        }

        $validated = $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'sender_name'          => ['nullable', 'string', 'max:150'],
            'sender_phone'         => ['nullable', 'string', 'max:30'],
            'transaction_sms_code' => ['nullable', 'string', 'max:50'],
            'screenshot'           => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
            // Pour carte
            'card_number' => ['nullable', 'string', 'min:16'],
            'card_holder' => ['nullable', 'string', 'max:150'],
            'expiry'      => ['nullable', 'string'],
            'cvv'         => ['nullable', 'string'],
        ]);

        $pm = \App\Models\PaymentMethod::findOrFail($validated['payment_method_id']);
        $methodType = strtolower($pm->name);

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('payments/screenshots', 'public');
        }

        $depositAmount = $reservation->depositDueAmount();

        \DB::transaction(function () use ($reservation, $pm, $depositAmount, $validated, $screenshotPath) {
            $txn = 'API-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5));

            \App\Models\Payment::create([
                'reservation_id'       => $reservation->id,
                'payment_kind'         => \App\Models\Payment::KIND_DEPOSIT,
                'payment_method'       => $pm->name,
                'amount'               => $depositAmount,
                'currency'             => \App\Models\SiteSetting::get('app_devise', 'DJF'),
                'transaction_id'       => $txn,
                'transaction_sms_code' => $validated['transaction_sms_code'] ?? null,
                'sender_name'          => $validated['sender_name'] ?? null,
                'sender_phone'         => $validated['sender_phone'] ?? null,
                'screenshot'           => $screenshotPath,
                'status'               => 'accepted', // Simulation
                'paid_at'              => now(),
                'notes'                => 'Paiement mobile via ' . $pm->name,
            ]);

            // Confirmation automatique par le système
            $reservation->update(['statut' => 'CONFIRMEE']);
        });

        // Unique email au partenaire lors de la confirmation
        try {
            $hotel = $reservation->chambre->typeChambre->hotel;
            $recipients = [];
            if ($hotel->user && $hotel->user->email) $recipients[] = $hotel->user->email;
            if ($hotel->admin_id && $hotel->admin_id != $hotel->user_id) {
                if ($hotel->admin && $hotel->admin->email) $recipients[] = $hotel->admin->email;
            }
            $recipients = array_unique($recipients);

            if (!empty($recipients)) {
                $mail = \Mail::to($recipients);
                $globalReceiver = \App\Models\SiteSetting::get('mail_resa_receiver');
                if ($globalReceiver && !in_array($globalReceiver, $recipients)) {
                    $mail->bcc($globalReceiver);
                }
                $mail->send(new \App\Mail\ReservationCreatedMail($reservation, forAdmin: true));
            }

            if ($reservation->email_client) {
                \Mail::to($reservation->email_client)->send(new \App\Mail\ReservationCreatedMail($reservation, forAdmin: false));
            }
        } catch (\Throwable $e) {
            \Log::error('Erreur envoi email confirmation API: '.$e->getMessage());
        }

        return response()->json([
            'message' => 'Paiement enregistré avec succès.',
            'booking' => $this->formatReservation($reservation->refresh())
        ]);
    }

    private function userOwnsReservation(Reservation $reservation, $user): bool
    {
        if ($reservation->user_id !== null && (int) $reservation->user_id === (int) $user->id) {
            return true;
        }

        return $reservation->user_id === null
            && strcasecmp((string) $reservation->email_client, (string) $user->email) === 0;
    }

    private function formatReservation(Reservation $r): array
    {
        $hotel = $r->chambre?->typeChambre?->hotel;
        $typeChambre = $r->chambre?->typeChambre;

        $statusMap = [
            'EN_ATTENTE' => 'pending',
            'CONFIRMEE' => 'confirmed',
            'ANNULEE' => 'cancelled',
            'TERMINEE' => 'completed',
        ];

        return [
            'id' => $r->id,
            'code' => $r->code_reservation,
            'user_id' => $r->user_id ?? 0,
            'room_id' => $r->chambre_id,
            'hotel_name' => $hotel?->nom,
            'room_name' => $typeChambre?->nom_type,
            'hotel_thumbnail' => $hotel?->mainImage?->url ? url('storage/' . $hotel->mainImage->url) : null,
            'check_in' => $r->date_debut?->format('Y-m-d'),
            'check_out' => $r->date_fin?->format('Y-m-d'),
            'guests' => $r->quantite,
            'total_price' => (float) $r->montant_total,
            'deposit_amount' => (float) $r->depositDueAmount(),
            'status' => $statusMap[$r->statut] ?? 'pending',
            'has_paid_deposit' => $r->hasPaidDeposit(),
            'created_at' => $r->created_at?->toIso8601String(),
        ];
    }
}
