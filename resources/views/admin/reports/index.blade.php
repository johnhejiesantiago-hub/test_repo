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
            <h2>Reports & Analytics</h2>
            
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#overview">Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.reports.bookings') }}">Booking Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.reports.income') }}">Income Reports</a>
                </li>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane fade show active" id="overview">
                    <!-- Monthly Trends -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Monthly Trends (Last 12 Months)</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Total Bookings</th>
                                            <th>Completed</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlyData as $data)
                                        <tr>
                                            <td>{{ $data['month'] }}</td>
                                            <td>{{ $data['total_bookings'] }}</td>
                                            <td>{{ $data['completed_bookings'] }}</td>
                                            <td>₱{{ number_format($data['revenue'], 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th>Total</th>
                                            <th>{{ array_sum(array_column($monthlyData, 'total_bookings')) }}</th>
                                            <th>{{ array_sum(array_column($monthlyData, 'completed_bookings')) }}</th>
                                            <th>₱{{ number_format(array_sum(array_column($monthlyData, 'revenue')), 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Driver Performance -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Top Performing Drivers</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Driver Name</th>
                                            <th>Total Trips</th>
                                            <th>Total Revenue</th>
                                            <th>Rating</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($driverPerformance as $driver)
                                        <tr>
                                            <td>{{ $driver->name }}</td>
                                            <td>{{ $driver->total_trips }}</td>
                                            <td>₱{{ number_format($driver->bookings_sum_fare_amount ?? 0, 2) }}</td>
                                            <td>{{ $driver->rating }} ⭐</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Popular Routes -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Most Popular Routes</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Pickup Location</th>
                                            <th>Dropoff Location</th>
                                            <th>Trips</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($popularRoutes as $route)
                                        <tr>
                                            <td>{{ $route->pickup_location }}</td>
                                            <td>{{ $route->dropoff_location }}</td>
                                            <td>{{ $route->total }}</td>
                                            <td>₱{{ number_format($route->revenue, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection