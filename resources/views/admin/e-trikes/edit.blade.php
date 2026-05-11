@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit E-Trike: {{ $eTrike->plate_number }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.e-trikes.update', $eTrike) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="plate_number" class="form-label">Plate Number *</label>
                            <input type="text" class="form-control @error('plate_number') is-invalid @enderror" 
                                   id="plate_number" name="plate_number" value="{{ old('plate_number', $eTrike->plate_number) }}" required>
                            @error('plate_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="model" class="form-label">Model *</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                   id="model" name="model" value="{{ old('model', $eTrike->model) }}" required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacity (Number of Passengers) *</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                   id="capacity" name="capacity" value="{{ old('capacity', $eTrike->capacity) }}" 
                                   min="1" max="6" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="driver_id" class="form-label">Assign Driver</label>
                            <select class="form-select @error('driver_id') is-invalid @enderror" id="driver_id" name="driver_id">
                                <option value="">No driver assigned</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id', $eTrike->driver_id) == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }} - {{ $driver->license_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="available" {{ old('status', $eTrike->status) == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="booked" {{ old('status', $eTrike->status) == 'booked' ? 'selected' : '' }}>Booked</option>
                                <option value="maintenance" {{ old('status', $eTrike->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.e-trikes.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update E-Trike</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection