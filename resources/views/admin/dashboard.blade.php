@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.drivers.index') }}">
                            <i class="bi bi-people"></i> Drivers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.e-trikes.index') }}">
                            <i class="bi bi-truck"></i> E-Trikes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.fare-rates.index') }}">
                            <i class="bi bi-calculator"></i> Fare Rates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.bookings.index') }}">
                            <i class="bi bi-calendar-check"></i> Manage Bookings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reports') }}">
                            <i class="bi bi-graph-up"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h2>Admin Dashboard</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="text-muted">Welcome back, {{ Auth::user()->name }}!</span>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Total Bookings</h6>
                                    <h2 class="mb-0">{{ $totalBookings ?? 0 }}</h2>
                                </div>
                                <i class="bi bi-calendar3 fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Pending Bookings</h6>
                                    <h2 class="mb-0">{{ $pendingBookings ?? 0 }}</h2>
                                </div>
                                <i class="bi bi-clock fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Completed Bookings</h6>
                                    <h2 class="mb-0">{{ $completedBookings ?? 0 }}</h2>
                                </div>
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Total Revenue</h6>
                                    <h2 class="mb-0">₱{{ number_format($totalRevenue ?? 0, 2) }}</h2>
                                </div>
                                <i class="bi bi-cash-stack fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Stats -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Drivers</h6>
                            <h3 class="card-title">{{ $totalDrivers ?? 0 }}</h3>
                            <small class="text-success">{{ $availableDrivers ?? 0 }} Available</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">E-Trikes</h6>
                            <h3 class="card-title">{{ $totalETrikes ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Passengers</h6>
                            <h3 class="card-title">{{ $totalPassengers ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Today's Bookings</h6>
                            <h3 class="card-title">{{ $todaysBookings ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Bookings Table -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Recent Bookings</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Booking #</th>
                                    <th>Passenger</th>
                                    <th>Pickup</th>
                                    <th>Dropoff</th>
                                    <th>Fare</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($recentBookings ?? []) as $booking)
                                <tr>
                                    <td>{{ $booking->booking_number }}</td>
                                    <td>{{ $booking->user->name }}</td>
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
                                    <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No bookings found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection