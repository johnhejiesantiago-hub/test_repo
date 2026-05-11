@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>E-Trike Management</h2>
                <a href="{{ route('admin.e-trikes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add E-Trike
                </a>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Plate Number</th>
                                    <th>Model</th>
                                    <th>Capacity</th>
                                    <th>Status</th>
                                    <th>Assigned Driver</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($eTrikes as $eTrike)
                                <tr>
                                    <td>{{ $eTrike->id }}</td>
                                    <td><strong>{{ $eTrike->plate_number }}</strong></td>
                                    <td>{{ $eTrike->model }}</td>
                                    <td>{{ $eTrike->capacity }} seats</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $eTrike->status == 'available' ? 'success' : 
                                            ($eTrike->status == 'booked' ? 'warning' : 'danger') 
                                        }}">
                                            {{ ucfirst($eTrike->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($eTrike->driver)
                                            {{ $eTrike->driver->name }}
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.e-trikes.edit', $eTrike) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.e-trikes.destroy', $eTrike) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this e-trike?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.e-trikes.toggle-status', $eTrike) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-secondary">
                                                {{ $eTrike->status == 'available' ? 'Set Maintenance' : 'Set Available' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-truck fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">No e-trikes found. Click "Add E-Trike" to get started.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                {{ $eTrikes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection