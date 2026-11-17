@extends('layouts.inventory')

@section('title', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Products</h2>
    <a href="{{ route('products.create') }}" class="btn btn-theme">+ Add Product</a>
</div>

<form method="GET" action="{{ route('products.index') }}" class="row g-2 mb-3">
    <div class="col-md-6">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or SKU...">
    </div>
    <div class="col-md-3">
        <select name="category" class="form-select">
            <option value="">All Categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-outline-theme w-100" type="submit">Filter</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle shadow-sm bg-white">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Category</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Price</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr class="{{ $product->isLowStock() || $product->isOutOfStock() ? 'row-alert' : '' }}">
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category }}</td>
                    <td class="text-end">{{ $product->quantity }}</td>
                    <td class="text-end">₱{{ number_format($product->unit_price, 2) }}</td>
                    <td>
                        @if ($product->isOutOfStock())
                            <span class="badge bg-danger">Out of Stock</span>
                        @elseif ($product->isLowStock())
                            <span class="badge bg-warning text-dark">Low Stock</span>
                        @else
                            <span class="badge bg-success">In Stock</span>
                        @endif
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-theme">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete {{ $product->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $products->links('pagination::bootstrap-5') }}
@endsection