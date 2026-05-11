@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add New E-Trike</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.e-trikes.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="plate_number" class="form-label">Plate Number *</label>
                            <input type="text" class="form-control @error('plate_number') is-invalid @enderror" 
                                   id="plate_number" name="plate_number" value="{{ old('plate_number') }}" 
                                   placeholder="e.g., ETK-1234" required>
                            @error('plate_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="model" class="form-label">Model *</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                   id="model" name="model" value="{{ old('model') }}" 
                                   placeholder="e.g., Bajaj RE, TVS King" required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacity (Number of Passengers) *</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                   id="capacity" name="capacity" value="{{ old('capacity', 3) }}" 
                                   min="1" max="6" required>
                            <small class="text-muted">Maximum 6 passengers</small>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="driver_id" class="form-label">Assign Driver (Optional)</label>
                            <select class="form-select @error('driver_id') is-invalid @enderror" id="driver_id" name="driver_id">
                                <option value="">No driver assigned</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }} - {{ $driver->license_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> After adding an e-trike, you can assign it to a driver from the e-trikes list.
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.e-trikes.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add E-Trike</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection