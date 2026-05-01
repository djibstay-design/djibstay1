<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use App\Models\Chambre;
use App\Models\Hotel;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user     = $request->user();
        $today    = Carbon::today();
        $monHotel = Hotel::where(fn($q) => $q->where('user_id',$user->id)->orWhere('admin_id',$user->id))
            ->withCount(['typesChambre','avis'])
            ->with(['typesChambre.chambres','images','mainImage'])
            ->first();

        if (!$monHotel) {
            return view('hotel_admin.dashboard', ['monHotel' => null] + $this->emptyData());
        }

        $resQ = Reservation::query()
            ->with('chambre.typeChambre.hotel')
            ->whereHas('chambre.typeChambre', fn($q) => $q->where('hotel_id', $monHotel->id));

        $chamQ = Chambre::query()
            ->whereHas('typeChambre', fn($q) => $q->where('hotel_id', $monHotel->id));

        // KPIs
        $totalRevenue    = (clone $resQ)->where('statut','CONFIRMEE')->sum('montant_total');
        $newBookings     = (clone $resQ)->where('date_reservation','>=',$today->copy()->subWeek())->count();
        $checkInToday    = (clone $resQ)->whereDate('date_debut',$today)->where('statut','CONFIRMEE')->count();
        $checkOutToday   = (clone $resQ)->whereDate('date_fin',$today)->where('statut','CONFIRMEE')->count();
        $enAttente       = (clone $resQ)->where('statut','EN_ATTENTE')->count();

        // Charts revenus
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $d   = $today->copy()->subMonths($i);
            $sum = (clone $resQ)->where('statut','CONFIRMEE')
                ->whereMonth('date_reservation',$d->month)
                ->whereYear('date_reservation',$d->year)
                ->sum('montant_total');
            $revenueData[] = ['month' => $d->locale('fr')->translatedFormat('M'), 'amount' => (float)$sum];
        }

        // Charts réservations 7j
        $guestsData = [];
        for ($i = 6; $i >= 0; $i--) {
            $d     = $today->copy()->subDays($i);
            $count = (clone $resQ)->whereDate('date_reservation',$d)->count();
            $guestsData[] = ['day' => $d->locale('fr')->translatedFormat('D'), 'count' => $count];
        }

        // Occupation
        $totalRooms       = $chamQ->count();
        $occupied         = (clone $chamQ)->where('etat','OCCUPEE')->count();
        $maintenance      = (clone $chamQ)->where('etat','MAINTENANCE')->count();
        $reserved         = (clone $resQ)->where('statut','CONFIRMEE')->where('date_debut','>',$today)->pluck('chambre_id')->unique()->count();
        $available        = max(0, $totalRooms - $occupied - $maintenance - $reserved);
        $occupancyPercent = $totalRooms > 0 ? round(($occupied/$totalRooms)*100) : 0;

        // Avis
        $avgRating    = (float) Avis::where('hotel_id',$monHotel->id)->avg('note') ?: 0;
        $reviewsCount = Avis::where('hotel_id',$monHotel->id)->count();

        // Activité
        $recentActivity   = (clone $resQ)->latest('date_reservation')->take(6)->get();
        $upcomingCheckins = (clone $resQ)->where('statut','CONFIRMEE')
            ->whereDate('date_debut','>=',$today)
            ->whereDate('date_debut','<=',$today->copy()->addDays(7))
            ->orderBy('date_debut')->take(5)->get();

        $reservationsByStatus = [
            'CONFIRMEE'  => (clone $resQ)->where('statut','CONFIRMEE')->count(),
            'EN_ATTENTE' => $enAttente,
            'ANNULEE'    => (clone $resQ)->where('statut','ANNULEE')->count(),
        ];

        return view('hotel_admin.dashboard', compact(
            'monHotel','totalRevenue','newBookings','checkInToday','checkOutToday',
            'enAttente','revenueData','guestsData','totalRooms','occupied',
            'maintenance','reserved','available','occupancyPercent','avgRating',
            'reviewsCount','recentActivity','upcomingCheckins','reservationsByStatus'
        ));
    }

    private function emptyData(): array
    {
        return [
            'totalRevenue'=>0,'newBookings'=>0,'checkInToday'=>0,'checkOutToday'=>0,
            'enAttente'=>0,'revenueData'=>[],'guestsData'=>[],'totalRooms'=>0,
            'occupied'=>0,'maintenance'=>0,'reserved'=>0,'available'=>0,
            'occupancyPercent'=>0,'avgRating'=>0,'reviewsCount'=>0,
            'recentActivity'=>collect(),'upcomingCheckins'=>collect(),
            'reservationsByStatus'=>['CONFIRMEE'=>0,'EN_ATTENTE'=>0,'ANNULEE'=>0],
        ];
    }
}