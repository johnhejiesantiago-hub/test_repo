<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\ETrike;
use App\Models\Notification;
use Illuminate\Http\Request;

class BookingManagementController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'driver', 'eTrike'])
            ->latest()
            ->paginate(20);
        
        $pendingCount = Booking::where('status', 'pending')->count();
        $confirmedCount = Booking::where('status', 'confirmed')->count();
        $completedCount = Booking::where('status', 'completed')->count();
        
        return view('admin.bookings.index', compact('bookings', 'pendingCount', 'confirmedCount', 'completedCount'));
    }
    
    public function show(Booking $booking)
    {
        $booking->load(['user', 'driver', 'eTrike']);
        $availableDrivers = Driver::where('status', 'available')
            ->whereHas('eTrike', function($query) {
                $query->where('status', 'available');
            })
            ->get();
            
        return view('admin.bookings.show', compact('booking', 'availableDrivers'));
    }
    
    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be confirmed.');
        }
        
        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
        
        // Notify passenger
        Notification::create([
            'user_id' => $booking->user_id,
            'title' => 'Booking Confirmed',
            'message' => "Your booking #{$booking->booking_number} has been confirmed. A driver will be assigned soon.",
            'type' => 'booking',
            'data' => ['booking_id' => $booking->id],
        ]);
        
        return back()->with('success', 'Booking confirmed successfully.');
    }
    
    public function assignDriver(Request $request, Booking $booking)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);
        
        $driver = Driver::find($request->driver_id);
        $eTrike = $driver->eTrike;
        
        if (!$eTrike) {
            return back()->with('error', 'This driver does not have an assigned e-trike.');
        }
        
        if ($eTrike->status !== 'available') {
            return back()->with('error', 'The assigned e-trike is not available.');
        }
        
        $booking->update([
            'driver_id' => $driver->id,
            'e_trike_id' => $eTrike->id,
            'status' => 'driver_assigned',
        ]);
        
        $eTrike->update(['status' => 'booked']);
        $driver->update(['status' => 'on_trip']);
        
        // Notify passenger
        Notification::create([
            'user_id' => $booking->user_id,
            'title' => 'Driver Assigned',
            'message' => "Driver {$driver->name} has been assigned to your booking #{$booking->booking_number}. E-Trike: {$eTrike->plate_number}",
            'type' => 'trip',
            'data' => ['booking_id' => $booking->id, 'driver_id' => $driver->id],
        ]);
        
        return back()->with('success', 'Driver assigned successfully.');
    }
    
    public function complete(Booking $booking)
    {
        if (!in_array($booking->status, ['driver_assigned', 'trip_started'])) {
            return back()->with('error', 'This booking cannot be completed.');
        }
        
        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        
        // Update driver stats
        if ($booking->driver) {
            $booking->driver->increment('total_trips');
            $booking->driver->update(['status' => 'available']);
        }
        
        // Update e-trike status
        if ($booking->eTrike) {
            $booking->eTrike->update(['status' => 'available']);
        }
        
        // Notify passenger
        Notification::create([
            'user_id' => $booking->user_id,
            'title' => 'Trip Completed',
            'message' => "Your trip #{$booking->booking_number} has been completed. Thank you for using QuickServe!",
            'type' => 'trip',
            'data' => ['booking_id' => $booking->id],
        ]);
        
        return back()->with('success', 'Trip marked as completed.');
    }
}