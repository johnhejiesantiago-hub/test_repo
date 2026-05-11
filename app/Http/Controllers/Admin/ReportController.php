<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Monthly statistics
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $bookings = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            $completedBookings = (clone $bookings)->where('status', 'completed');
            
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'total_bookings' => $bookings->count(),
                'completed_bookings' => $completedBookings->count(),
                'revenue' => $completedBookings->sum('fare_amount'),
            ];
        }
        
        // Driver performance
        $driverPerformance = Driver::withCount('bookings')
            ->withSum('bookings', 'fare_amount')
            ->orderBy('total_trips', 'desc')
            ->limit(10)
            ->get();
        
        // Popular routes
        $popularRoutes = Booking::where('status', 'completed')
            ->selectRaw('pickup_location, dropoff_location, COUNT(*) as total, SUM(fare_amount) as revenue')
            ->groupBy('pickup_location', 'dropoff_location')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.reports.index', compact('monthlyData', 'driverPerformance', 'popularRoutes'));
    }
    
    public function bookings(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        $bookings = Booking::with(['user', 'driver'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(20);
        
        $summary = [
            'total' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')->count(),
            'cancelled' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', ['cancelled_by_passenger', 'cancelled_by_driver'])->count(),
            'revenue' => Booking::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')->sum('fare_amount'),
        ];
        
        return view('admin.reports.bookings', compact('bookings', 'summary', 'startDate', 'endDate'));
    }
    
    public function income(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;
        $availableYears = range(2023, Carbon::now()->year);
        
        $monthlyIncome = [];
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            
            $monthlyIncome[] = [
                'month' => Carbon::create($year, $month, 1)->format('F'),
                'bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
                'completed' => Booking::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'completed')->count(),
                'revenue' => Booking::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'completed')->sum('fare_amount'),
            ];
        }
        
        $totalRevenue = array_sum(array_column($monthlyIncome, 'revenue'));
        $totalBookings = array_sum(array_column($monthlyIncome, 'bookings'));
        
        return view('admin.reports.income', compact('monthlyIncome', 'totalRevenue', 'totalBookings', 'year', 'availableYears'));
    }
}