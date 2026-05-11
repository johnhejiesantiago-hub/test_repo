@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.drivers.index') }}">
                            <i class="bi bi-people"></i> Drivers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.e-trikes.index') }}">
                            <i class="bi bi-truck"></i> E-Trikes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.fare-rates.index') }}">
                            <i class="bi bi-calculator"></i> Fare Rates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.bookings.index') }}">
                            <i class="bi bi-calendar-check"></i> Manage Bookings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.reports') }}">
                            <i class="bi bi-graph-up"></i> Reports
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Income Reports</h2>
                <a href="{{ route('admin.reports') }}" class="btn btn-secondary">Back to Reports</a>
            </div>
            
            <!-- Year Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Select Year</label>
                            <select name="year" class="form-select">
                                @foreach($availableYears as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Summary -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Total Revenue for {{ $year }}</h6>
                            <h2>₱{{ number_format($totalRevenue, 2) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Total Bookings for {{ $year }}</h6>
                            <h2>{{ $totalBookings }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Breakdown -->
            <div class="card">
                <div class="card-header">
                    <h5>Monthly Income Breakdown for {{ $year }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Month</th>
                                    <th>Total Bookings</th>
                                    <th>Completed Trips</th>
                                    <th>Revenue</th>
                                    <th>Average per Trip</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyIncome as $data)
                                <tr>
                                    <td>{{ $data['month'] }}</td>
                                    <td>{{ $data['bookings'] }}</td>
                                    <td>{{ $data['completed'] }}</td>
                                    <td>₱{{ number_format($data['revenue'], 2) }}</td>
                                    <td>
                                        ₱{{ number_format($data['completed'] > 0 ? $data['revenue'] / $data['completed'] : 0, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>Total</th>
                                    <th>{{ $totalBookings }}</th>
                                    <th>{{ array_sum(array_column($monthlyIncome, 'completed')) }}</th>
                                    <th>₱{{ number_format($totalRevenue, 2) }}</th>
                                    <th>₱{{ number_format($totalRevenue / max(array_sum(array_column($monthlyIncome, 'completed')), 1), 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection