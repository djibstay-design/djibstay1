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
        $today = Carbon::today();

        $userId = $user->id;

        $reservationsQuery = Reservation::query()->with('chambre.typeChambre.hotel');
        if ($user->role !== 'SUPER_ADMIN') {
            $reservationsQuery->whereHas('chambre.typeChambre.hotel', fn ($q) => $q->where('user_id', $userId)->orWhere('admin_id', $userId));
        }

        $chambresQuery = Chambre::query()->whereHas('typeChambre.hotel', function ($q) use ($user, $userId) {
            if ($user->role !== 'SUPER_ADMIN') {
                $q->where('user_id', $userId)->orWhere('admin_id', $userId);
            }
        });

        $hotelsQuery = $user->role === 'SUPER_ADMIN'
            ? Hotel::query()
            : Hotel::where(fn ($q) => $q->where('user_id', $userId)->orWhere('admin_id', $userId));

        // KPIs
        $totalRevenue = (clone $reservationsQuery)->where('statut', 'CONFIRMEE')->sum('montant_total');
        $lastWeekRevenue = (clone $reservationsQuery)->where('statut', 'CONFIRMEE')
            ->where('date_reservation', '>=', $today->copy()->subWeek())->sum('montant_total');
        $twoWeeksAgoRevenue = (clone $reservationsQuery)->where('statut', 'CONFIRMEE')
            ->whereBetween('date_reservation', [$today->copy()->subWeeks(2), $today->copy()->subWeek()])->sum('montant_total');
        $revenueChange = $twoWeeksAgoRevenue > 0
            ? round((($lastWeekRevenue - $twoWeeksAgoRevenue) / $twoWeeksAgoRevenue) * 100, 2)
            : 0;

        $newBookings = (clone $reservationsQuery)->where('date_reservation', '>=', $today->copy()->subWeek())->count();
        $prevBookings = (clone $reservationsQuery)->whereBetween('date_reservation', [$today->copy()->subWeeks(2), $today->copy()->subWeek()])->count();
        $bookingsChange = $prevBookings > 0 ? round((($newBookings - $prevBookings) / $prevBookings) * 100, 2) : 0;

        $checkInToday = (clone $reservationsQuery)->whereDate('date_debut', $today)->where('statut', 'CONFIRMEE')->count();
        $checkInLastWeek = (clone $reservationsQuery)->whereDate('date_debut', $today->copy()->subWeek())->where('statut', 'CONFIRMEE')->count();
        $checkInChange = $checkInLastWeek > 0 ? round((($checkInToday - $checkInLastWeek) / $checkInLastWeek) * 100, 2) : 0;

        $checkOutToday = (clone $reservationsQuery)->whereDate('date_fin', $today)->where('statut', 'CONFIRMEE')->count();
        $checkOutLastWeek = (clone $reservationsQuery)->whereDate('date_fin', $today->copy()->subWeek())->where('statut', 'CONFIRMEE')->count();
        $checkOutChange = $checkOutLastWeek > 0 ? round((($checkOutToday - $checkOutLastWeek) / $checkOutLastWeek) * 100, 2) : 0;

        // Guests chart (reservations per day this week)
        $guestsData = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = $today->copy()->subDays($i);
            $count = (clone $reservationsQuery)->where('statut', 'CONFIRMEE')
                ->where(function ($q) use ($d) {
                    $q->where('date_debut', '<=', $d)->where('date_fin', '>=', $d);
                })->count();
            $guestsData[] = ['day' => $d->locale('fr')->translatedFormat('D'), 'count' => $count];
        }

        // Revenue chart (last 8 months)
        $revenueData = [];
        for ($i = 7; $i >= 0; $i--) {
            $d = $today->copy()->subMonths($i);
            $sum = (clone $reservationsQuery)->where('statut', 'CONFIRMEE')
                ->whereMonth('date_reservation', $d->month)
                ->whereYear('date_reservation', $d->year)
                ->sum('montant_total');
            $revenueData[] = ['month' => $d->locale('fr')->translatedFormat('M'), 'amount' => (float) $sum];
        }

        // Bookings chart (booked vs canceled this year)
        $bookedData = [];
        $canceledData = [];
        for ($i = 1; $i <= 12; $i++) {
            $booked = (clone $reservationsQuery)->where('statut', 'CONFIRMEE')
                ->whereMonth('date_reservation', $i)->whereYear('date_reservation', $today->year)->count();
            $canceled = (clone $reservationsQuery)->where('statut', 'ANNULEE')
                ->whereMonth('date_reservation', $i)->whereYear('date_reservation', $today->year)->count();
            $bookedData[] = $booked;
            $canceledData[] = $canceled;
        }
        $totalBooked = array_sum($bookedData);
        $totalCanceled = array_sum($canceledData);

        // Room occupancy
        $totalRooms = $chambresQuery->count();
        $occupied = (clone $chambresQuery)->where('etat', 'OCCUPEE')->count();
        $notReady = (clone $chambresQuery)->where('etat', 'MAINTENANCE')->count();
        $reserved = (clone $reservationsQuery)->where('statut', 'CONFIRMEE')
            ->where('date_debut', '>', $today)
            ->pluck('chambre_id')->unique()->count();
        $available = $totalRooms - $occupied - $notReady - $reserved;
        if ($available < 0) {
            $available = 0;
        }
        $occupancyPercent = $totalRooms > 0 ? round(($occupied / $totalRooms) * 100) : 0;

        // Overall ratings
        $avisQuery = Avis::query()->whereHas('hotel', function ($q) use ($user, $userId) {
            if ($user->role !== 'SUPER_ADMIN') {
                $q->where('user_id', $userId)->orWhere('admin_id', $userId);
            }
        });
        $avgRating = (float) $avisQuery->avg('note') ?: 0;
        $reviewsCount = $avisQuery->count();

        // Recent activity (last 10 reservations)
        $recentActivity = (clone $reservationsQuery)->with('chambre.typeChambre.hotel')
            ->latest('date_reservation')->take(10)->get();

        // Booking list (today)
        $bookingList = (clone $reservationsQuery)->with('chambre.typeChambre.hotel')
            ->where('statut', 'CONFIRMEE')
            ->where(function ($q) use ($today) {
                $q->whereDate('date_debut', $today)->orWhereDate('date_fin', $today);
            })
            ->orderBy('date_debut')
            ->take(10)
            ->get();

        $hotels = $hotelsQuery->withCount(['typesChambre', 'avis'])->get();

        // Réservations par statut
        $reservationsByStatus = [
            'CONFIRMEE' => (clone $reservationsQuery)->where('statut', 'CONFIRMEE')->count(),
            'EN_ATTENTE' => (clone $reservationsQuery)->where('statut', 'EN_ATTENTE')->count(),
            'ANNULEE' => (clone $reservationsQuery)->where('statut', 'ANNULEE')->count(),
        ];

        // Prochains check-in (7 jours)
        $upcomingCheckins = (clone $reservationsQuery)->with('chambre.typeChambre.hotel')
            ->where('statut', 'CONFIRMEE')
            ->whereDate('date_debut', '>=', $today)
            ->whereDate('date_debut', '<=', $today->copy()->addDays(7))
            ->orderBy('date_debut')
            ->take(8)
            ->get();

        // Performance par hôtel (revenus, nb réservations)
        $hotelsPerformance = $hotelsQuery->withCount(['typesChambre', 'avis'])
            ->get()
            ->map(function ($h) use ($reservationsQuery) {
                $rev = (clone $reservationsQuery)->whereHas('chambre.typeChambre', fn ($q) => $q->where('hotel_id', $h->id))
                    ->where('statut', 'CONFIRMEE')->sum('montant_total');
                $nbRes = (clone $reservationsQuery)->whereHas('chambre.typeChambre', fn ($q) => $q->where('hotel_id', $h->id))->count();
                return [
                    'hotel' => $h,
                    'revenue' => $rev,
                    'reservations_count' => $nbRes,
                ];
            })
            ->sortByDesc('revenue')
            ->take(5)
            ->values();

        return view('admin.dashboard', [
            'hotels' => $hotels,
            'totalRevenue' => $totalRevenue,
            'revenueChange' => $revenueChange,
            'newBookings' => $newBookings,
            'bookingsChange' => $bookingsChange,
            'checkInToday' => $checkInToday,
            'checkInChange' => $checkInChange,
            'checkOutToday' => $checkOutToday,
            'checkOutChange' => $checkOutChange,
            'guestsData' => $guestsData,
            'revenueData' => $revenueData,
            'bookedData' => $bookedData,
            'canceledData' => $canceledData,
            'totalBooked' => $totalBooked,
            'totalCanceled' => $totalCanceled,
            'totalRooms' => $totalRooms,
            'occupied' => $occupied,
            'available' => $available,
            'reserved' => $reserved,
            'notReady' => $notReady,
            'occupancyPercent' => $occupancyPercent,
            'avgRating' => $avgRating,
            'reviewsCount' => $reviewsCount,
            'recentActivity' => $recentActivity,
            'bookingList' => $bookingList,
            'reservationsByStatus' => $reservationsByStatus,
            'upcomingCheckins' => $upcomingCheckins,
            'hotelsPerformance' => $hotelsPerformance,
        ]);
    }
}
