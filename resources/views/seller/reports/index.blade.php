@extends('layouts.seller')

@section('title', 'Sales Reports')

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sales Reports</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('seller.reports.export-sales', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download"></i> Export
            </a>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>
</div>

<!-- Date Range Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('seller.reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label for="period_type" class="form-label">Period Type</label>
                <select class="form-select" id="period_type" name="period_type">
                    <option value="daily" {{ $periodType == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="monthly" {{ $periodType == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ $periodType == 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Report Navigation -->
<div class="row mb-4">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('seller.reports.index') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.reports.sales') }}">Sales History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.reports.menu-performance') }}">Menu Performance</a>
            </li>
        </ul>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Sales</h6>
                        <h2 class="mb-0">Rp {{ number_format($totalSales, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
                <p class="card-text mt-2">
                    <small>{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</small>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Completed Orders</h6>
                        <h2 class="mb-0">{{ $orderCount }}</h2>
                    </div>
                    <i class="fas fa-shopping-cart fa-2x"></i>
                </div>
                <p class="card-text mt-2">
                    <small>{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</small>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Average Order Value</h6>
                        <h2 class="mb-0">Rp {{ $orderCount > 0 ? number_format($totalSales / $orderCount, 0, ',', '.') : 0 }}</h2>
                    </div>
                    <i class="fas fa-calculator fa-2x"></i>
                </div>
                <p class="card-text mt-2">
                    <small>{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</small>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Sales Chart -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Sales Trend ({{ ucfirst($periodType) }})</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Selling Items -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Selling Items</h5>
            </div>
            <div class="card-body">
                @if($topSellingItems->isEmpty())
                    <p class="text-center text-muted my-5">No sales data available for this period</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSellingItems as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->total_quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('seller.reports.menu-performance') }}" class="btn btn-sm btn-primary">View All Menu Performance</a>
            </div>
        </div>
    </div>

    <!-- Order Status Distribution -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Order Status Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="orderStatusChart" height="260"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesLabels) !!},
            datasets: [{
                label: 'Sales (Rp)',
                data: {!! json_encode($salesData) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 2,
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Sales: Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Order Status Chart
    const statusLabels = [];
    const statusData = [];
    const statusColors = {
        'pending': 'rgba(255, 193, 7, 0.8)',
        'processing': 'rgba(13, 202, 240, 0.8)',
        'completed': 'rgba(25, 135, 84, 0.8)',
        'cancelled': 'rgba(220, 53, 69, 0.8)'
    };
    const backgroundColors = [];

    @foreach($orderStatusDistribution as $status => $count)
        statusLabels.push('{{ ucfirst($status) }}');
        statusData.push({{ $count }});
        backgroundColors.push(statusColors['{{ $status }}'] || 'rgba(108, 117, 125, 0.8)');
    @endforeach

    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: backgroundColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
});
</script>
@endpush
@endsection

