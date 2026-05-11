<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\ETrike;
use App\Models\FareRate;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $activeBookings = $user->bookings()
            ->whereNotIn('status', ['completed', 'cancelled_by_passenger', 'cancelled_by_driver'])
            ->latest()
            ->get();
        
        $completedBookings = $user->bookings()
            ->where('status', 'completed')
            ->latest()
            ->limit(10)
            ->get();
        
        $recentNotifications = $user->notifications()
            ->latest()
            ->limit(5)
            ->get();
        
        $unreadCount = $user->notifications()
            ->where('is_read', false)
            ->count();
        
        return view('passenger.dashboard', compact(
            'activeBookings',
            'completedBookings',
            'recentNotifications',
            'unreadCount'
        ));
    }
    
    public function create()
    {
        $fareRate = FareRate::getActiveRate();
        return view('passenger.bookings.create', compact('fareRate'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'distance_km' => 'required|numeric|min:0',
            'fare_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'pickup_location' => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'distance_km' => $request->distance_km,
            'fare_amount' => $request->fare_amount,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);
        
        // Create notification for admin
        Notification::create([
            'user_id' => 1, // Admin user ID (assuming admin is user ID 1)
            'title' => 'New Booking Request',
            'message' => "New booking #{$booking->booking_number} requires assignment.",
            'type' => 'booking',
            'data' => ['booking_id' => $booking->id],
        ]);
        
        return redirect()->route('passenger.bookings.show', $booking)
            ->with('success', 'Booking created successfully! Waiting for driver assignment.');
    }
    
    public function show(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('passenger.bookings.show', compact('booking'));
    }
    
    public function cancel(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        if (!in_array($booking->status, ['pending', 'confirmed', 'driver_assigned'])) {
            return back()->with('error', 'This booking cannot be cancelled at this stage.');
        }
        
        $booking->update([
            'status' => 'cancelled_by_passenger',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason ?? 'Cancelled by passenger',
        ]);
        
        // Notify admin
        Notification::create([
            'user_id' => 1,
            'title' => 'Booking Cancelled',
            'message' => "Booking #{$booking->booking_number} was cancelled by passenger.",
            'type' => 'booking',
            'data' => ['booking_id' => $booking->id],
        ]);
        
        return redirect()->route('passenger.bookings.history')
            ->with('success', 'Booking cancelled successfully.');
    }
    
    public function history()
    {
        $bookings = Auth::user()
            ->bookings()
            ->latest()
            ->paginate(10);
        
        return view('passenger.bookings.history', compact('bookings'));
    }
}