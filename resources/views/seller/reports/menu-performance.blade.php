@extends('layouts.seller')

@section('title', 'Menu Performance')

@section('seller-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Menu Performance</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<!-- Date Range Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('seller.reports.menu-performance') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
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
                <a class="nav-link" href="{{ route('seller.reports.index') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.reports.sales') }}">Sales History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('seller.reports.menu-performance') }}">Menu Performance</a>
            </li>
        </ul>
    </div>
</div>

<!-- Category Performance -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Category Performance ({{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }})</h5>
    </div>
    <div class="card-body">
        @if($categoryPerformance->isEmpty())
            <p class="text-center text-muted my-5">No sales data available for this period</p>
        @else
            <div class="row">
                <div class="col-md-8">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryPerformance as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td class="text-end">Rp {{ number_format($category->total_sales, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Menu Performance Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Menu Item Performance ({{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }})</h5>
    </div>
    <div class="card-body">
        @if($menuPerformance->isEmpty())
            <p class="text-center text-muted my-5">No sales data available for this period</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Menu Item</th>
                            <th>Category</th>
                            <th class="text-center">Quantity Sold</th>
                            <th class="text-center">Orders</th>
                            <th class="text-end">Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menuPerformance as $menu)
                            <tr>
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->category_name }}</td>
                                <td class="text-center">{{ $menu->total_quantity }}</td>
                                <td class="text-center">{{ $menu->order_count }}</td>
                                <td class="text-end">Rp {{ number_format($menu->total_sales, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $menuPerformance->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(!$categoryPerformance->isEmpty())
    // Category Performance Chart
    const categoryLabels = [];
    const categorySales = [];
    const categoryColors = [
        'rgba(13, 110, 253, 0.8)',
        'rgba(25, 135, 84, 0.8)',
        'rgba(13, 202, 240, 0.8)',
        'rgba(255, 193, 7, 0.8)',
        'rgba(220, 53, 69, 0.8)',
        'rgba(108, 117, 125, 0.8)',
        'rgba(111, 66, 193, 0.8)',
        'rgba(214, 51, 132, 0.8)',
        'rgba(32, 201, 151, 0.8)',
        'rgba(253, 126, 20, 0.8)'
    ];

    @foreach($categoryPerformance as $index => $category)
        categoryLabels.push('{{ $category->name }}');
        categorySales.push({{ $category->total_sales }});
    @endforeach

    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categorySales,
                backgroundColor: categoryColors.slice(0, categoryLabels.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${context.label}: Rp ${value.toLocaleString('id-ID')} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush
@endsection

