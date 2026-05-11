@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Booking Details: {{ $booking->booking_number }}</h2>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Bookings
                </a>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Booking Information</h5>
                        <span class="badge bg-{{ 
                            $booking->status == 'completed' ? 'success' : 
                            ($booking->status == 'pending' ? 'warning' : 
                            ($booking->status == 'cancelled_by_passenger' ? 'danger' : 'info')) 
                        }} fs-6">
                            {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Passenger Information -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Passenger Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr><th>Name:</th><td>{{ $booking->user->name }}</td></tr>
                                <tr><th>Email:</th><td>{{ $booking->user->email }}</td></tr>
                                <tr><th>Phone:</th><td>{{ $booking->user->phone ?? 'N/A' }}</td></tr>
                                <tr><th>Address:</th><td>{{ $booking->user->address ?? 'N/A' }}</td></tr>
                            </table>
                        </div>
                        
                        <!-- Trip Information -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Trip Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr><th>Pickup:</th><td>{{ $booking->pickup_location }}</td></tr>
                                <tr><th>Dropoff:</th><td>{{ $booking->dropoff_location }}</td></tr>
                                <tr><th>Distance:</th><td>{{ number_format($booking->distance_km, 2) }} km</td></tr>
                                <tr><th>Fare:</th><td><strong>₱{{ number_format($booking->fare_amount, 2) }}</strong></td></tr>
                                <tr><th>Booking Date:</th><td>{{ $booking->created_at->format('F d, Y h:i A') }}</td></tr>
                             scandal
                        </div>
                    </div>
                    
                    <!-- Driver Information -->
                    @if($booking->driver)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">Driver Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr><th>Driver Name:</th><td>{{ $booking->driver->name }}</td></tr>
                                <tr><th>Driver Phone:</th><td>{{ $booking->driver->phone }}</td></tr>
                                <tr><th>License:</th><td>{{ $booking->driver->license_number }}</td></tr>
                                @if($booking->eTrike)
                                <tr><th>E-Trike Plate:</th><td><strong>{{ $booking->eTrike->plate_number }}</strong></td></tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Notes -->
                    @if($booking->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-chat-dots"></i> <strong>Passenger Notes:</strong><br>
                                {{ $booking->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            @if($booking->status == 'pending')
                                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Confirm Booking
                                    </button>
                                </form>
                            @endif
                            
                            @if(in_array($booking->status, ['confirmed', 'pending']))
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignDriverModal">
                                    <i class="bi bi-person-check"></i> Assign Driver
                                </button>
                            @endif
                            
                            @if(in_array($booking->status, ['driver_assigned', 'trip_started']))
                                <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info">
                                        <i class="bi bi-flag"></i> Complete Trip
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Driver Modal -->
<div class="modal fade" id="assignDriverModal" tabindex="-1">
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
                            @foreach($availableDrivers as $driver)
                            <option value="{{ $driver->id }}">
                                {{ $driver->name }} - {{ $driver->eTrike ? 'ETrike: '.$driver->eTrike->plate_number : 'No ETrike' }}
                            </option>
                            @endforeach
                        </select>
                        @if($availableDrivers->isEmpty())
                            <small class="text-danger">No available drivers with e-trikes found.</small>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Driver</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection