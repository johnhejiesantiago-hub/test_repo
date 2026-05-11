@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Book an E-Trike Ride</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('passenger.bookings.store') }}" id="bookingForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="pickup_location" class="form-label">Pickup Location *</label>
                            <input type="text" class="form-control @error('pickup_location') is-invalid @enderror" 
                                   id="pickup_location" name="pickup_location" required>
                            @error('pickup_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="dropoff_location" class="form-label">Drop-off Location *</label>
                            <input type="text" class="form-control @error('dropoff_location') is-invalid @enderror" 
                                   id="dropoff_location" name="dropoff_location" required>
                            @error('dropoff_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="distance_km" class="form-label">Estimated Distance (km)</label>
                                    <input type="number" step="0.1" class="form-control @error('distance_km') is-invalid @enderror" 
                                           id="distance_km" name="distance_km" value="2.0">
                                    @error('distance_km')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fare_amount" class="form-label">Estimated Fare (₱)</label>
                                    <input type="number" step="0.01" class="form-control @error('fare_amount') is-invalid @enderror" 
                                           id="fare_amount" name="fare_amount" readonly>
                                    @error('fare_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="e.g., Pick up near the blue gate, etc."></textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Fare rates:
                            <ul class="mb-0 mt-2">
                                <li>Base Fare: ₱{{ number_format($fareRate->base_fare, 2) }}</li>
                                <li>Per Kilometer: ₱{{ number_format($fareRate->per_km_rate, 2) }}</li>
                                <li>Booking Fee: ₱{{ number_format($fareRate->booking_fee, 2) }}</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Confirm Booking
                            </button>
                            <a href="{{ route('passenger.dashboard') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-calculate fare when distance changes
    const distanceInput = document.getElementById('distance_km');
    const fareInput = document.getElementById('fare_amount');
    const baseFare = {{ $fareRate->base_fare }};
    const perKmRate = {{ $fareRate->per_km_rate }};
    const bookingFee = {{ $fareRate->booking_fee }};
    
    function calculateFare() {
        let distance = parseFloat(distanceInput.value) || 0;
        let fare = baseFare + (distance * perKmRate) + bookingFee;
        fareInput.value = fare.toFixed(2);
    }
    
    distanceInput.addEventListener('input', calculateFare);
    calculateFare(); // Initial calculation
</script>
@endpush
@endsection