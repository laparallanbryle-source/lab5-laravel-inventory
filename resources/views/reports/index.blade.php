@extends('layouts.inventory')

@section('title', 'Inventory Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-2">
    <h2 class="mb-0">Inventory Report</h2>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-theme">Print</button>
        <a href="{{ route('reports.export') }}" class="btn btn-theme">Export CSV</a>
    </div>
</div>
<p class="text-muted">Generated on {{ now()->format('M d, Y h:i A') }}</p>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <h6>Total Products</h6><h3 class="mb-0">{{ $totalProducts }}</h3>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <h6>Total Inventory Value</h6><h3 class="mb-0">₱{{ number_format($totalValue, 2) }}</h3>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <h6>Items Needing Reorder</h6><h3 class="mb-0">{{ $lowStockProducts->count() }}</h3>
        </div></div>
    </div>
</div>

<h5>Summary by Category</h5>
<div class="table-responsive mb-4">
    <table class="table table-striped bg-white shadow-sm">
        <thead><tr><th>Category</th><th class="text-end">Products</th><th class="text-end">Total Qty</th><th class="text-end">Total Value</th></tr></thead>
        <tbody>
            @foreach ($categorySummary as $row)
                <tr>
                    <td>{{ $row->category }}</td>
                    <td class="text-end">{{ $row->items }}</td>
                    <td class="text-end">{{ $row->total_qty }}</td>
                    <td class="text-end">₱{{ number_format($row->total_value, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h5>Top 5 Products by Inventory Value</h5>
<div class="table-responsive mb-4">
    <table class="table table-striped bg-white shadow-sm">
        <thead><tr><th>Product</th><th class="text-end">Qty</th><th class="text-end">Unit Price</th><th class="text-end">Total Value</th></tr></thead>
        <tbody>
            @foreach ($topProducts as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td class="text-end">{{ $p->quantity }}</td>
                    <td class="text-end">₱{{ number_format($p->unit_price, 2) }}</td>
                    <td class="text-end">₱{{ number_format($p->total_value, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h5>Items at or Below Reorder Level</h5>
<div class="table-responsive">
    <table class="table table-striped bg-white shadow-sm">
        <thead><tr><th>SKU</th><th>Product</th><th class="text-end">Qty</th><th class="text-end">Reorder Level</th><th>Status</th></tr></thead>
        <tbody>
            @forelse ($lowStockProducts as $p)
                <tr class="row-alert">
                    <td>{{ $p->sku }}</td>
                    <td>{{ $p->name }}</td>
                    <td class="text-end">{{ $p->quantity }}</td>
                    <td class="text-end">{{ $p->reorder_level }}</td>
                    <td>
                        @if ($p->isOutOfStock())
                            <span class="badge bg-danger">Out of Stock</span>
                        @else
                            <span class="badge bg-warning text-dark">Low Stock</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">All products are sufficiently stocked.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection