@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Booking Details: {{ $booking->booking_number }}</h5>
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
                        <!-- Trip Information -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Trip Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="35%">Pickup Location:</th>
                                    <td>{{ $booking->pickup_location }}</td>
                                </tr>
                                <tr>
                                    <th>Dropoff Location:</th>
                                    <td>{{ $booking->dropoff_location }}</td>
                                </tr>
                                <tr>
                                    <th>Distance:</th>
                                    <td>{{ number_format($booking->distance_km, 2) }} km</td>
                                </tr>
                                <tr>
                                    <th>Fare Amount:</th>
                                    <td><strong>₱{{ number_format($booking->fare_amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Booking Date:</th>
                                    <td>{{ $booking->created_at->format('F d, Y h:i A') }}</td>
                                </tr>
                                @if($booking->scheduled_at)
                                <tr>
                                    <th>Scheduled For:</th>
                                    <td>{{ $booking->scheduled_at->format('F d, Y h:i A') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        
                        <!-- Driver Information -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Driver & Vehicle Information</h6>
                            @if($booking->driver)
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="35%">Driver Name:</th>
                                        <td><strong>{{ $booking->driver->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Driver Phone:</th>
                                        <td>{{ $booking->driver->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>License Number:</th>
                                        <td>{{ $booking->driver->license_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Driver Rating:</th>
                                        <td>{{ $booking->driver->rating }} ⭐ ({{ $booking->driver->total_trips }} trips)</td>
                                    </tr>
                                    @if($booking->eTrike)
                                    <tr>
                                        <th>E-Trike Plate:</th>
                                        <td><strong>{{ $booking->eTrike->plate_number }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>E-Trike Model:</th>
                                        <td>{{ $booking->eTrike->model }}</td>
                                    </tr>
                                    @endif
                                </table>
                            @else
                                <p class="text-muted">No driver assigned yet.</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Timeline / Status History -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">Trip Timeline</h6>
                            <div class="timeline">
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <strong>Booking Created</strong><br>
                                        <small class="text-muted">{{ $booking->created_at->format('F d, Y h:i A') }}</small>
                                    </div>
                                </div>
                                
                                @if($booking->confirmed_at)
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <strong>Booking Confirmed</strong><br>
                                        <small class="text-muted">{{ $booking->confirmed_at->format('F d, Y h:i A') }}</small>
                                    </div>
                                </div>
                                @endif
                                
                                @if($booking->driver_id)
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <i class="bi bi-person-check-fill text-info fs-4"></i>
                                    </div>
                                    <div>
                                        <strong>Driver Assigned</strong><br>
                                        <small class="text-muted">Driver {{ $booking->driver->name }} assigned to your trip</small>
                                    </div>
                                </div>
                                @endif
                                
                                @if($booking->completed_at)
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <i class="bi bi-flag-fill text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <strong>Trip Completed</strong><br>
                                        <small class="text-muted">{{ $booking->completed_at->format('F d, Y h:i A') }}</small>
                                    </div>
                                </div>
                                @endif
                                
                                @if($booking->cancelled_at)
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                                    </div>
                                    <div>
                                        <strong>Booking Cancelled</strong><br>
                                        <small class="text-muted">{{ $booking->cancelled_at->format('F d, Y h:i A') }}</small>
                                        @if($booking->cancellation_reason)
                                            <p class="text-muted mb-0">Reason: {{ $booking->cancellation_reason }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    @if($booking->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-chat-dots"></i> <strong>Additional Notes:</strong><br>
                                {{ $booking->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            @if(in_array($booking->status, ['pending', 'confirmed', 'driver_assigned']))
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    <i class="bi bi-x-circle"></i> Cancel Booking
                                </button>
                            @endif
                            
                            <a href="{{ route('passenger.bookings.history') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('passenger.bookings.cancel', $booking) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this booking?</p>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for cancellation (optional)</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" 
                                  placeholder="Tell us why you're cancelling..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection