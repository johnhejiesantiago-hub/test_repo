@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>My Booking History</h2>
                <a href="{{ route('passenger.bookings.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> New Booking
                </a>
            </div>
            
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Booking #</th>
                                    <th>Pickup Location</th>
                                    <th>Dropoff Location</th>
                                    <th>Fare</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    <td><strong>{{ $booking->booking_number }}</strong></td>
                                    <td>{{ Str::limit($booking->pickup_location, 30) }}</td>
                                    <td>{{ Str::limit($booking->dropoff_location, 30) }}</td>
                                    <td>₱{{ number_format($booking->fare_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $booking->status == 'completed' ? 'success' : 
                                            ($booking->status == 'pending' ? 'warning' : 
                                            ($booking->status == 'cancelled_by_passenger' ? 'danger' : 
                                            ($booking->status == 'cancelled_by_driver' ? 'danger' : 'info'))) 
                                        }}">
                                            {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('passenger.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        
                                        @if(in_array($booking->status, ['pending', 'confirmed', 'driver_assigned']))
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                                <i class="bi bi-x-circle"></i> Cancel
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                
                                <!-- Cancel Modal for each booking -->
                                <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('passenger.bookings.cancel', $booking) }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Cancel Booking #{{ $booking->booking_number }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to cancel this booking?</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason (optional)</label>
                                                        <textarea class="form-control" name="reason" rows="2"></textarea>
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
                                
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">You haven't made any bookings yet.</p>
                                        <a href="{{ route('passenger.bookings.create') }}" class="btn btn-primary mt-2">
                                            Book Your First Ride
                                        </a>
                                    </td>
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