@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add New Fare Rate</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.fare-rates.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Rate Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="e.g., Standard Rate, Peak Hour Rate, Holiday Rate" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="base_fare" class="form-label">Base Fare (₱) *</label>
                                    <input type="number" step="0.01" class="form-control @error('base_fare') is-invalid @enderror" 
                                           id="base_fare" name="base_fare" value="{{ old('base_fare', 40.00) }}" required>
                                    @error('base_fare')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="per_km_rate" class="form-label">Per Kilometer Rate (₱) *</label>
                                    <input type="number" step="0.01" class="form-control @error('per_km_rate') is-invalid @enderror" 
                                           id="per_km_rate" name="per_km_rate" value="{{ old('per_km_rate', 10.00) }}" required>
                                    @error('per_km_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="per_minute_rate" class="form-label">Per Minute Rate (₱) *</label>
                                    <input type="number" step="0.01" class="form-control @error('per_minute_rate') is-invalid @enderror" 
                                           id="per_minute_rate" name="per_minute_rate" value="{{ old('per_minute_rate', 2.00) }}" required>
                                    @error('per_minute_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="booking_fee" class="form-label">Booking Fee (₱)</label>
                                    <input type="number" step="0.01" class="form-control @error('booking_fee') is-invalid @enderror" 
                                           id="booking_fee" name="booking_fee" value="{{ old('booking_fee', 0) }}">
                                    @error('booking_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Note:</strong> This fare rate will become the active rate once created. All previous rates will be deactivated.
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.fare-rates.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Fare Rate & Activate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection