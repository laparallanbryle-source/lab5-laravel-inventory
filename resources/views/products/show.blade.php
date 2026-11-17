@extends('layouts.inventory')

@section('title', $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">{{ $product->name }}</h2>
    <div>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-theme">Edit</a>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>SKU:</strong> {{ $product->sku }}</p>
                <p><strong>Category:</strong> {{ $product->category }}</p>
                <p><strong>Supplier:</strong> {{ $product->supplier ?? '—' }}</p>
                <p><strong>Description:</strong> {{ $product->description ?? '—' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
                <p><strong>Reorder Level:</strong> {{ $product->reorder_level }}</p>
                <p><strong>Unit Price:</strong> ₱{{ number_format($product->unit_price, 2) }}</p>
                <p><strong>Total Value:</strong> ₱{{ number_format($product->total_value, 2) }}</p>
                <p><strong>Status:</strong>
                    @if ($product->isOutOfStock())
                        <span class="badge bg-danger">Out of Stock</span>
                    @elseif ($product->isLowStock())
                        <span class="badge bg-warning text-dark">Low Stock</span>
                    @else
                        <span class="badge bg-success">In Stock</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<h5>Recent Transactions</h5>
<div class="table-responsive">
    <table class="table table-sm bg-white shadow-sm">
        <thead><tr><th>Date</th><th>Type</th><th>Qty</th><th>Reference</th></tr></thead>
        <tbody>
            @forelse ($transactions as $t)
                <tr>
                    <td>{{ $t->created_at->format('M d, Y h:i A') }}</td>
                    <td>{{ strtoupper($t->type) }}</td>
                    <td>{{ $t->quantity }}</td>
                    <td>{{ $t->reference ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted">No transactions yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection