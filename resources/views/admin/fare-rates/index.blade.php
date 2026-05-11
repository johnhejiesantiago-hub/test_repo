@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Fare Rate Management</h2>
                <a href="{{ route('admin.fare-rates.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Fare Rate
                </a>
            </div>
            
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> The active fare rate will be used for all fare calculations.
            </div>
            
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Base Fare</th>
                                    <th>Per KM Rate</th>
                                    <th>Per Minute Rate</th>
                                    <th>Booking Fee</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fareRates as $fareRate)
                                <tr class="{{ $fareRate->is_active ? 'table-success' : '' }}">
                                    <td>{{ $fareRate->id }}</td>
                                    <td><strong>{{ $fareRate->name }}</strong></td>
                                    <td>₱{{ number_format($fareRate->base_fare, 2) }}</td>
                                    <td>₱{{ number_format($fareRate->per_km_rate, 2) }}</td>
                                    <td>₱{{ number_format($fareRate->per_minute_rate, 2) }}</td>
                                    <td>₱{{ number_format($fareRate->booking_fee, 2) }}</td>
                                    <td>
                                        @if($fareRate->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.fare-rates.edit', $fareRate) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        
                                        @if(!$fareRate->is_active)
                                            <form action="{{ route('admin.fare-rates.activate', $fareRate) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle"></i> Activate
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if(!$fareRate->is_active)
                                            <form action="{{ route('admin.fare-rates.destroy', $fareRate) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this fare rate?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-calculator fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No fare rates found. Click "Add Fare Rate" to get started.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                {{ $fareRates->links() }}
            </div>
        </div>
    </div>
</div>
@endsection