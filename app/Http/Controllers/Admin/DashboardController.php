<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\ETrike;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics for dashboard cards
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $totalDrivers = Driver::count();
        $availableDrivers = Driver::where('status', 'available')->count();
        $totalETrikes = ETrike::count();
        $totalPassengers = User::where('role', 'passenger')->count();
        
        // Today's bookings
        $todaysBookings = Booking::whereDate('created_at', Carbon::today())->count();
        
        // Total revenue from completed bookings
        $totalRevenue = Booking::where('status', 'completed')->sum('fare_amount');
        
        // Recent bookings for table display
        $recentBookings = Booking::with(['user', 'driver'])
            ->latest()
            ->take(10)
            ->get();
        
        // Booking trend for chart (last 7 days)
        $bookingTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Booking::whereDate('created_at', $date)->count();
            $bookingTrend[] = [
                'date' => $date->format('M d'),
                'count' => $count,
            ];
        }
        
        return view('admin.dashboard', compact(
            'totalBookings',
            'pendingBookings',
            'completedBookings',
            'totalDrivers',
            'availableDrivers',
            'totalETrikes',
            'totalPassengers',
            'todaysBookings',
            'totalRevenue',
            'recentBookings',
            'bookingTrend'
        ));
    }
}