@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 p-0 bg-dark vh-100">
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('admin.drivers.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-people"></i> Drivers
                </a>
                <a href="{{ route('admin.e-trikes.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-truck"></i> E-Trikes
                </a>
                <a href="{{ route('admin.fare-rates.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-calculator"></i> Fare Rates
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="list-group-item list-group-item-action bg-dark text-white active">
                    <i class="bi bi-calendar-check"></i> Manage Bookings
                </a>
                <a href="{{ route('admin.reports') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-graph-up"></i> Reports
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <h2>Manage Bookings</h2>
            
            <!-- Status Summary -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5>Pending</h5>
                            <h3>{{ $pendingCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5>Confirmed</h5>
                            <h3>{{ $confirmedCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5>Completed</h5>
                            <h3>{{ $completedCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bookings Table -->
            <div class="card">
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
                                    <th>Driver</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->booking_number }}</td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ Str::limit($booking->pickup_location, 25) }}</td>
                                    <td>{{ Str::limit($booking->dropoff_location, 25) }}</td>
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
                                    <td>{{ $booking->driver ? $booking->driver->name : 'Not assigned' }}</td>
                                    <td>
                                        @if($booking->status == 'pending')
                                            <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                            </form>
                                        @endif
                                        
                                        @if($booking->status == 'confirmed' || $booking->status == 'pending')
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignDriverModal{{ $booking->id }}">
                                                Assign Driver
                                            </button>
                                        @endif
                                        
                                        @if(in_array($booking->status, ['driver_assigned', 'trip_started']))
                                            <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-info">Complete</button>
                                            </form>
                                        @endif
                                        
                                        <a href="{{ route('passenger.bookings.show', $booking) }}" class="btn btn-sm btn-secondary">View</a>
                                    </td>
                                </tr>
                                
                                <!-- Assign Driver Modal -->
                                <div class="modal fade" id="assignDriverModal{{ $booking->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.bookings.assign-driver', $booking) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Assign Driver for Booking #{{ $booking->booking_number }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Select Driver</label>
                                                        <select name="driver_id" class="form-select" required>
                                                            <option value="">Choose driver...</option>
                                                            @foreach(\App\Models\Driver::where('status', 'available')->get() as $driver)
                                                            <option value="{{ $driver->id }}">
                                                                {{ $driver->name }} - {{ $driver->eTrike ? 'ETrike: '.$driver->eTrike->plate_number : 'No ETrike' }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Assign</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No bookings found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection