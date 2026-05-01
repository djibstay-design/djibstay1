<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use App\Models\Chambre;
use App\Models\Hotel;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->role === 'SUPER_ADMIN') {
            return $this->superAdminDashboard();
        }

        return $this->adminHotelDashboard($user);
    }

    // ══════════════════════════════════════
    //  SUPER ADMIN — Tout voir
    // ══════════════════════════════════════
    private function superAdminDashboard(): View
    {
        $today = Carbon::today();

        $reservationsQuery = Reservation::query()->with('chambre.typeChambre.hotel');
        $chambresQuery     = Chambre::query();
        $hotelsQuery       = Hotel::query();

        // KPIs
        $totalRevenue      = (clone $reservationsQuery)->where('statut','CONFIRMEE')->sum('montant_total');
        $lastWeekRevenue   = (clone $reservationsQuery)->where('statut','CONFIRMEE')->where('date_reservation','>=',$today->copy()->subWeek())->sum('montant_total');
        $twoWeeksRevenue   = (clone $reservationsQuery)->where('statut','CONFIRMEE')->whereBetween('date_reservation',[$today->copy()->subWeeks(2),$today->copy()->subWeek()])->sum('montant_total');
        $revenueChange     = $twoWeeksRevenue > 0 ? round((($lastWeekRevenue - $twoWeeksRevenue) / $twoWeeksRevenue) * 100, 2) : 0;

        $newBookings       = (clone $reservationsQuery)->where('date_reservation','>=',$today->copy()->subWeek())->count();
        $prevBookings      = (clone $reservationsQuery)->whereBetween('date_reservation',[$today->copy()->subWeeks(2),$today->copy()->subWeek()])->count();
        $bookingsChange    = $prevBookings > 0 ? round((($newBookings - $prevBookings) / $prevBookings) * 100, 2) : 0;

        $checkInToday      = (clone $reservationsQuery)->whereDate('date_debut',$today)->where('statut','CONFIRMEE')->count();
        $checkInLastWeek   = (clone $reservationsQuery)->whereDate('date_debut',$today->copy()->subWeek())->where('statut','CONFIRMEE')->count();
        $checkInChange     = $checkInLastWeek > 0 ? round((($checkInToday - $checkInLastWeek) / $checkInLastWeek) * 100, 2) : 0;

        $checkOutToday     = (clone $reservationsQuery)->whereDate('date_fin',$today)->where('statut','CONFIRMEE')->count();
        $checkOutLastWeek  = (clone $reservationsQuery)->whereDate('date_fin',$today->copy()->subWeek())->where('statut','CONFIRMEE')->count();
        $checkOutChange    = $checkOutLastWeek > 0 ? round((($checkOutToday - $checkOutLastWeek) / $checkOutLastWeek) * 100, 2) : 0;

        // Charts
        $guestsData = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = $today->copy()->subDays($i);
            $count = (clone $reservationsQuery)->where('statut','CONFIRMEE')->where(fn($q) => $q->where('date_debut','<=',$d)->where('date_fin','>=',$d))->count();
            $guestsData[] = ['day' => $d->locale('fr')->translatedFormat('D'), 'count' => $count];
        }

        $revenueData = [];
        for ($i = 7; $i >= 0; $i--) {
            $d   = $today->copy()->subMonths($i);
            $sum = (clone $reservationsQuery)->where('statut','CONFIRMEE')->whereMonth('date_reservation',$d->month)->whereYear('date_reservation',$d->year)->sum('montant_total');
            $revenueData[] = ['month' => $d->locale('fr')->translatedFormat('M'), 'amount' => (float) $sum];
        }

        // Occupation réelle basée sur les réservations
        $totalRooms       = $chambresQuery->count();
        $occupied         = (clone $reservationsQuery)->where('statut','CONFIRMEE')->where('date_debut','<=',$today)->where('date_fin','>',$today)->sum('quantite');
        $notReady         = (clone $chambresQuery)->where('etat','MAINTENANCE')->count();
        $reserved         = (clone $reservationsQuery)->where('statut','CONFIRMEE')->where('date_debut','>',$today)->sum('quantite');
        $available        = max(0, $totalRooms - $occupied - $notReady);
        $occupancyPercent = $totalRooms > 0 ? round(($occupied / $totalRooms) * 100) : 0;

        // Avis
        $avgRating    = (float) Avis::avg('note') ?: 0;
        $reviewsCount = Avis::count();

        // Activité récente
        $recentActivity = (clone $reservationsQuery)->latest('date_reservation')->take(10)->get();

        // Prochains check-ins
        $upcomingCheckins = (clone $reservationsQuery)->where('statut','CONFIRMEE')->whereDate('date_debut','>=',$today)->whereDate('date_debut','<=',$today->copy()->addDays(7))->orderBy('date_debut')->take(8)->get();

        // Statuts
        $reservationsByStatus = [
            'CONFIRMEE'  => (clone $reservationsQuery)->where('statut','CONFIRMEE')->count(),
            'EN_ATTENTE' => (clone $reservationsQuery)->where('statut','EN_ATTENTE')->count(),
            'ANNULEE'    => (clone $reservationsQuery)->where('statut','ANNULEE')->count(),
        ];

        // Performance hôtels
        $hotelsPerformanceQuery = Hotel::query()
            ->select('hotels.*')
            ->leftJoin('types_chambre', 'hotels.id', '=', 'types_chambre.hotel_id')
            ->leftJoin('chambres', 'types_chambre.id', '=', 'chambres.type_id')
            ->leftJoin('reservations', function($join) {
                $join->on('chambres.id', '=', 'reservations.chambre_id')
                     ->where('reservations.statut', '=', 'CONFIRMEE');
            })
            ->groupBy('hotels.id', 'hotels.nom', 'hotels.ville', 'hotels.adresse', 'hotels.description', 'hotels.user_id', 'hotels.admin_id', 'hotels.created_at', 'hotels.updated_at')
            ->selectRaw('COALESCE(SUM(reservations.montant_total), 0) as revenue')
            ->selectRaw('COUNT(reservations.id) as reservations_count')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $hotelsPerformance = $hotelsPerformanceQuery->map(function ($h) {
            return ['hotel' => $h, 'revenue' => (float) $h->revenue, 'reservations_count' => (int) $h->reservations_count];
        });

        $hotels = $hotelsQuery->withCount(['typesChambre','avis'])->get();

        return view('admin.dashboard', compact(
            'hotels','totalRevenue','revenueChange','newBookings','bookingsChange',
            'checkInToday','checkInChange','checkOutToday','checkOutChange',
            'guestsData','revenueData','totalRooms','occupied','available',
            'reserved','notReady','occupancyPercent','avgRating','reviewsCount',
            'recentActivity','upcomingCheckins','reservationsByStatus','hotelsPerformance'
        ));
    }

    // ══════════════════════════════════════
    //  ADMIN HÔTEL — Son hôtel uniquement
    // ══════════════════════════════════════
    private function adminHotelDashboard($user): View
    {
        $today = Carbon::today();

        // L'hôtel de cet admin uniquement
        $monHotel = Hotel::where(fn($q) => $q->where('user_id',$user->id)->orWhere('admin_id',$user->id))
            ->withCount(['typesChambre','avis'])
            ->first();

        if (!$monHotel) {
            return view('hotel_admin.dashboard', [
                'monHotel'             => null,
                'totalRevenue'         => 0,
                'revenueChange'        => 0,
                'newBookings'          => 0,
                'bookingsChange'       => 0,
                'checkInToday'         => 0,
                'checkOutToday'        => 0,
                'guestsData'           => [],
                'revenueData'          => [],
                'totalRooms'           => 0,
                'occupied'             => 0,
                'available'            => 0,
                'reserved'             => 0,
                'maintenance'          => 0,
                'occupancyPercent'     => 0,
                'avgRating'            => 0,
                'reviewsCount'         => 0,
                'recentActivity'       => collect(),
                'upcomingCheckins'     => collect(),
                'reservationsByStatus' => ['CONFIRMEE'=>0,'EN_ATTENTE'=>0,'ANNULEE'=>0],
                'typesChambre'         => collect(),
                'chambres'             => collect(),
                'enAttente'            => 0,
            ]);
        }

        // Réservations de CET hôtel uniquement
        $reservationsQuery = Reservation::query()
            ->with('chambre.typeChambre.hotel')
            ->whereHas('chambre.typeChambre', fn($q) => $q->where('hotel_id', $monHotel->id));

        // Chambres de CET hôtel uniquement
        $chambresQuery = Chambre::query()
            ->whereHas('typeChambre', fn($q) => $q->where('hotel_id', $monHotel->id));

        // KPIs
        $totalRevenue    = (clone $reservationsQuery)->where('statut','CONFIRMEE')->sum('montant_total');
        $lastWeekRevenue = (clone $reservationsQuery)->where('statut','CONFIRMEE')->where('date_reservation','>=',$today->copy()->subWeek())->sum('montant_total');
        $twoWeeksRevenue = (clone $reservationsQuery)->where('statut','CONFIRMEE')->whereBetween('date_reservation',[$today->copy()->subWeeks(2),$today->copy()->subWeek()])->sum('montant_total');
        $revenueChange   = $twoWeeksRevenue > 0 ? round((($lastWeekRevenue - $twoWeeksRevenue) / $twoWeeksRevenue) * 100, 2) : 0;

        $newBookings     = (clone $reservationsQuery)->where('date_reservation','>=',$today->copy()->subWeek())->count();
        $prevBookings    = (clone $reservationsQuery)->whereBetween('date_reservation',[$today->copy()->subWeeks(2),$today->copy()->subWeek()])->count();
        $bookingsChange  = $prevBookings > 0 ? round((($newBookings - $prevBookings) / $prevBookings) * 100, 2) : 0;

        $checkInToday    = (clone $reservationsQuery)->whereDate('date_debut',$today)->where('statut','CONFIRMEE')->count();
        $checkOutToday   = (clone $reservationsQuery)->whereDate('date_fin',$today)->where('statut','CONFIRMEE')->count();

        // Charts
        $guestsData = [];
        for ($i = 6; $i >= 0; $i--) {
            $d     = $today->copy()->subDays($i);
            $count = (clone $reservationsQuery)->where('statut','CONFIRMEE')->where(fn($q) => $q->where('date_debut','<=',$d)->where('date_fin','>=',$d))->count();
            $guestsData[] = ['day' => $d->locale('fr')->translatedFormat('D'), 'count' => $count];
        }

        $revenueData = [];
        for ($i = 7; $i >= 0; $i--) {
            $d   = $today->copy()->subMonths($i);
            $sum = (clone $reservationsQuery)->where('statut','CONFIRMEE')->whereMonth('date_reservation',$d->month)->whereYear('date_reservation',$d->year)->sum('montant_total');
            $revenueData[] = ['month' => $d->locale('fr')->translatedFormat('M'), 'amount' => (float) $sum];
        }

        // Occupation réelle basée sur les réservations
        $totalRooms       = $chambresQuery->count();
        $occupied         = (clone $reservationsQuery)->where('statut','CONFIRMEE')->where('date_debut','<=',$today)->where('date_fin','>',$today)->sum('quantite');
        $maintenance      = (clone $chambresQuery)->where('etat','MAINTENANCE')->count();
        $reserved         = (clone $reservationsQuery)->where('statut','CONFIRMEE')->where('date_debut','>',$today)->sum('quantite');
        $available        = max(0, $totalRooms - $occupied - $maintenance);
        $occupancyPercent = $totalRooms > 0 ? round(($occupied / $totalRooms) * 100) : 0;

        // Avis
        $avgRating    = (float) Avis::where('hotel_id',$monHotel->id)->avg('note') ?: 0;
        $reviewsCount = Avis::where('hotel_id',$monHotel->id)->count();

        // Activité récente
        $recentActivity = (clone $reservationsQuery)->latest('date_reservation')->take(8)->get();

        // Prochains check-ins
        $upcomingCheckins = (clone $reservationsQuery)
            ->where('statut','CONFIRMEE')
            ->whereDate('date_debut','>=',$today)
            ->whereDate('date_debut','<=',$today->copy()->addDays(7))
            ->orderBy('date_debut')
            ->take(6)->get();

        // Statuts
        $reservationsByStatus = [
            'CONFIRMEE'  => (clone $reservationsQuery)->where('statut','CONFIRMEE')->count(),
            'EN_ATTENTE' => (clone $reservationsQuery)->where('statut','EN_ATTENTE')->count(),
            'ANNULEE'    => (clone $reservationsQuery)->where('statut','ANNULEE')->count(),
            
        ];

        // Types de chambres et chambres de cet hôtel
        $typesChambre = $monHotel->typesChambre()->with(['chambres','images'])->get();
        $chambres     = (clone $chambresQuery)->with('typeChambre')->get();
        $enAttente = $reservationsByStatus['EN_ATTENTE'];

        return view('hotel_admin.dashboard', compact(
            'monHotel','totalRevenue','revenueChange','newBookings','bookingsChange',
            'checkInToday','checkOutToday','guestsData','revenueData',
            'totalRooms','occupied','available','reserved','maintenance','occupancyPercent',
            'avgRating','reviewsCount','recentActivity','upcomingCheckins',
            'reservationsByStatus','typesChambre','chambres','enAttente'
        ));
    }
}