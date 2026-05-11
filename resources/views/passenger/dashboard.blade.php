@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>My Dashboard</h2>
                    <p class="text-muted">Welcome back, {{ Auth::user()->name }}!</p>
                </div>
                <a href="{{ route('passenger.bookings.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Book a Ride
                </a>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Active Bookings</h6>
                            <h2 class="mb-0">{{ $activeBookings->count() ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-clock-history fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Completed Trips</h6>
                            <h2 class="mb-0">{{ $completedBookings->count() ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Notifications</h6>
                            <h2 class="mb-0">{{ $unreadCount ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-bell fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Bookings -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Active Bookings</h5>
        </div>
        <div class="card-body">
            @if(isset($activeBookings) && $activeBookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Booking #</th>
                                <th>Pickup</th>
                                <th>Dropoff</th>
                                <th>Fare</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeBookings as $booking)
                            <tr>
                                <td>{{ $booking->booking_number }}</td>
                                <td>{{ Str::limit($booking->pickup_location, 30) }}</td>
                                <td>{{ Str::limit($booking->dropoff_location, 30) }}</td>
                                <td>₱{{ number_format($booking->fare_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $booking->status == 'completed' ? 'success' : 
                                        ($booking->status == 'pending' ? 'warning' : 
                                        ($booking->status == 'cancelled_by_passenger' ? 'danger' : 'info')) 
                                    }}">
                                        {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('passenger.bookings.show', $booking) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center py-3">No active bookings. <a href="{{ route('passenger.bookings.create') }}">Book a ride now!</a></p>
            @endif
        </div>
    </div>
    
    <!-- Recent Notifications -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Recent Notifications</h5>
        </div>
        <div class="card-body">
            @if(isset($recentNotifications) && $recentNotifications->count() > 0)
                <div class="list-group">
                    @foreach($recentNotifications as $notification)
                    <div class="list-group-item {{ !$notification->is_read ? 'bg-light' : '' }}">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $notification->title }}</h6>
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">{{ $notification->message }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-center py-3">No notifications yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection