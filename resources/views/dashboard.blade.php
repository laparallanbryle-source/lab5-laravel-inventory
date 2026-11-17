@extends('layouts.inventory')

@section('title', 'Dashboard')

@section('content')
<h2 class="mb-4">Dashboard</h2>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-white shadow-sm" style="background-color: #6D57D6;">
            <div class="card-body">
                <h6 class="card-title">Total Products</h6>
                <h3 class="mb-0">{{ $totalProducts }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-white shadow-sm" style="background-color: #586BD6;">
            <div class="card-body">
                <h6 class="card-title">Low Stock</h6>
                <h3 class="mb-0">{{ $lowStockCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-white shadow-sm" style="background-color: #A372D6;">
            <div class="card-body">
                <h6 class="card-title">Out of Stock</h6>
                <h3 class="mb-0">{{ $outOfStockCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-white shadow-sm" style="background-color: #C161D6;">
            <div class="card-body">
                <h6 class="card-title">Inventory Value</h6>
                <h3 class="mb-0">₱{{ number_format($inventoryValue, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

@if ($lowStockProducts->count() > 0)
    <div class="alert alert-warning alert-low-stock">
        <strong>⚠ Low Stock Alert:</strong>
        {{ $lowStockProducts->count() }} product(s) are at or below their reorder level.
        <ul class="mb-0 mt-2">
            @foreach ($lowStockProducts->take(5) as $p)
                <li>
                    <a href="{{ route('products.show', $p) }}">{{ $p->name }}</a>
                    — {{ $p->quantity }} left (reorder at {{ $p->reorder_level }})
                </li>
            @endforeach
        </ul>
        @if ($lowStockProducts->count() > 5)
            <small>and {{ $lowStockProducts->count() - 5 }} more...</small>
        @endif
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-7">
        <div class="card shadow-sm p-3">
            <h6>Inventory Value by Category</h6>
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card shadow-sm p-3">
            <h6>Stock Status Distribution</h6>
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<h5>Recently Added Products</h5>
<div class="table-responsive">
    <table class="table table-striped bg-white shadow-sm">
        <thead>
            <tr><th>SKU</th><th>Name</th><th>Category</th><th class="text-end">Qty</th><th>Added</th></tr>
        </thead>
        <tbody>
            @forelse ($recentProducts as $p)
                <tr>
                    <td>{{ $p->sku }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->category }}</td>
                    <td class="text-end">{{ $p->quantity }}</td>
                    <td>{{ $p->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">No products yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    const categoryLabels = @json($categorySummary->pluck('category'));
    const categoryValues = @json($categorySummary->pluck('total_value'));

    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Total Value (₱)',
                data: categoryValues,
                backgroundColor: ['#6D57D6', '#586BD6', '#A372D6', '#C161D6', '#8E7CE0', '#B59AE8']
            }]
        },
        options: { responsive: true }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [{{ $inStockCount }}, {{ $lowStockCount }}, {{ $outOfStockCount }}],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: { responsive: true }
    });
</script>
@endpush