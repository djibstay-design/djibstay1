<?php

namespace App\Http\Controllers;

use App\Mail\ReservationCreatedMail;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReservationPaymentController extends Controller
{
    public function show(Reservation $reservation): View|RedirectResponse
    {
        $reservation->load('chambre.typeChambre.hotel');
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        if ($reservation->hasPaidDeposit()) {
            return redirect()
                ->route('reservations.confirmation', $reservation)
                ->with('success', "L'acompte est déjà enregistré.");
        }

        $depositAmount = $reservation->depositDueAmount();
        $balanceAmount = max(0, round((float) $reservation->montant_total - (float) $depositAmount, 2));

        return view('reservations.payment', [
            'reservation'    => $reservation,
            'depositAmount'  => $depositAmount,
            'balanceAmount'  => $balanceAmount,
            'depositPercent' => \App\Models\SiteSetting::get('resa_acompte_percent', Reservation::DEPOSIT_PERCENT),
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function store(Request $request, Reservation $reservation): RedirectResponse
    {
        $reservation->load('chambre.typeChambre.hotel');

        if ($reservation->hasPaidDeposit()) {
            return redirect()
                ->route('reservations.confirmation', $reservation)
                ->with('success', 'Paiement déjà enregistré.');
        }

        // 1. Validation de base
        $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'accept_conditions' => ['accepted'],
        ]);

        $pm = PaymentMethod::findOrFail($request->payment_method_id);
        $methodType = strtolower($pm->nom);

        // 2. Validation spécifique selon la méthode
        if (in_array($methodType, ['waafi', 'dmoney', 'cac pay'])) {
            $request->validate([
                'sender_name'          => ['required', 'string', 'max:150'],
                'sender_phone'         => ['required', 'string', 'max:30'],
                'transaction_sms_code' => ['required', 'string', 'max:50'],
                'screenshot'           => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
            ]);
        } elseif (str_contains($methodType, 'card') || str_contains($methodType, 'mastercard')) {
            $request->validate([
                'card_number' => ['required', 'string', 'min:16'],
                'card_holder' => ['required', 'string', 'max:150'],
                'expiry'      => ['required', 'string'],
                'cvv'         => ['required', 'string', 'digits_between:3,4'],
            ]);
        }

        // 3. Traitement de la preuve (screenshot)
        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('payments/screenshots', 'public');
        }

        $depositAmount = $reservation->depositDueAmount();

        // 4. Transaction protégée contre les doubles paiements
        DB::transaction(function () use ($reservation, $pm, $depositAmount, $request, $screenshotPath) {
            $locked = Reservation::query()->lockForUpdate()->find($reservation->id);
            if (!$locked || $locked->hasPaidDeposit()) {
                return;
            }

            $txn = 'DJ-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5));

            Payment::create([
                'reservation_id'       => $locked->id,
                'payment_kind'         => Payment::KIND_DEPOSIT,
                'payment_method'       => $pm->nom,
                'amount'               => $depositAmount,
                'currency'             => \App\Models\SiteSetting::get('app_devise', 'DJF'),
                'transaction_id'       => $txn,
                'transaction_sms_code' => $request->transaction_sms_code,
                'sender_name'          => $request->sender_name,
                'sender_phone'         => $request->sender_phone,
                'screenshot'           => $screenshotPath,
                'status'               => 'accepted', // Simulation
                'paid_at'              => now(),
                'notes'                => 'Paiement via ' . $pm->nom . ($request->card_number ? ' (Carte ' . substr($request->card_number,-4) . ')' : ''),
            ]);

            // Confirmation automatique de la réservation par le système
            $locked->update(['statut' => 'CONFIRMEE']);
        });

        $reservation->refresh();

        if (!$reservation->hasPaidDeposit()) {
            return back()->withInput()->withErrors([
                'payment' => "Le paiement n'a pas pu être enregistré. Réessayez.",
            ]);
        }

        // 6. Envoi de l'unique email au partenaire et au client (Confirmation finale)
        try {
            $hotel = $reservation->chambre->typeChambre->hotel;
            $recipients = [];

            // 1. Le propriétaire (user_id)
            if ($hotel->user && $hotel->user->email) {
                $recipients[] = $hotel->user->email;
            }

            // 2. Le gestionnaire (admin_id) si différent
            if ($hotel->admin_id && $hotel->admin_id != $hotel->user_id) {
                if ($hotel->admin && $hotel->admin->email) {
                    $recipients[] = $hotel->admin->email;
                }
            }

            $recipients = array_unique($recipients);

            if (!empty($recipients)) {
                $mail = Mail::to($recipients);
                
                // Optionnel : BCC au destinataire global (Super Admin plateforme)
                $globalReceiver = \App\Models\SiteSetting::get('mail_resa_receiver');
                if ($globalReceiver && !in_array($globalReceiver, $recipients)) {
                    $mail->bcc($globalReceiver);
                }

                $mail->send(new ReservationCreatedMail($reservation, forAdmin: true));
            }

            // 3. Le Client
            Mail::to($reservation->email_client)->send(new ReservationCreatedMail($reservation, forAdmin: false));

        } catch (\Throwable $e) {
            Log::error('Email de réservation non envoyé : ' . $e->getMessage());
        }

        $percent = \App\Models\SiteSetting::get('resa_acompte_percent', Reservation::DEPOSIT_PERCENT);
        return redirect()
            ->route('reservations.confirmation', $reservation)
            ->with('success', 'Acompte de ' . $percent . '% enregistré. Votre réservation est transmise à l’hôtel.');
    }
}