@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Driver Management</h2>
                <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Driver
                </a>
            </div>
            
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>License</th>
                                    <th>Status</th>
                                    <th>Rating</th>
                                    <th>Trips</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($drivers as $driver)
                                <tr>
                                    <td>{{ $driver->id }}</td>
                                    <td>{{ $driver->name }}</td>
                                    <td>{{ $driver->email }}</td>
                                    <td>{{ $driver->phone }}</td>
                                    <td>{{ $driver->license_number }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $driver->status == 'available' ? 'success' : 
                                            ($driver->status == 'on_trip' ? 'warning' : 'secondary') 
                                        }}">
                                            {{ ucfirst($driver->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $driver->rating }} ⭐</td>
                                    <td>{{ $driver->total_trips }}</td>
                                    <td>
                                        <a href="{{ route('admin.drivers.edit', $driver) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.drivers.destroy', $driver) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this driver?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.drivers.toggle-status', $driver) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-secondary">
                                                {{ $driver->status == 'available' ? 'Offline' : 'Online' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No drivers found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                {{ $drivers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection