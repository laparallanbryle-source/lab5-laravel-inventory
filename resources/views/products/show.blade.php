{{-- Ginagamit ang master layout. --}}
@extends('layouts.inventory')

{{-- Ang pamagat sa browser tab ay ang pangalan ng produkto. --}}
@section('title', $product->name)

{{-- Simula ng laman na ilalagay sa content area. --}}
@section('content')
{{-- HEADER: pangalan ng produkto sa kaliwa at mga button sa kanan. --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">{{ $product->name }}</h2>
    <div>
        {{-- Edit: papunta sa edit form ng produktong ito. --}}
        <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-theme">Edit</a>
        {{-- Back: bumabalik sa listahan ng products. --}}
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

{{-- DETALYE NG PRODUKTO: nasa loob ng card. --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            {{-- KALIWANG KOLUM: pangkalahatang impormasyon. --}}
            <div class="col-md-6">
                <p><strong>SKU:</strong> {{ $product->sku }}</p>
                <p><strong>Category:</strong> {{ $product->category }}</p>
                {{-- Kapag walang supplier o description (null), ipinapakita ang "—" gamit ang ?? --}}
                <p><strong>Supplier:</strong> {{ $product->supplier ?? '—' }}</p>
                <p><strong>Description:</strong> {{ $product->description ?? '—' }}</p>
            </div>
            {{-- KANANG KOLUM: stock at presyo. --}}
            <div class="col-md-6">
                <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
                <p><strong>Reorder Level:</strong> {{ $product->reorder_level }}</p>
                {{-- number_format(..., 2) = may kuwit at 2 decimal places. --}}
                <p><strong>Unit Price:</strong> ₱{{ number_format($product->unit_price, 2) }}</p>
                {{-- total_value ay accessor ng Product model: quantity x unit_price. --}}
                <p><strong>Total Value:</strong> ₱{{ number_format($product->total_value, 2) }}</p>
                <p><strong>Status:</strong>
                    {{-- Wala nang stock: pulang badge. --}}
                    @if ($product->isOutOfStock())
                        <span class="badge bg-danger">Out of Stock</span>
                    {{-- Mababa ang stock: dilaw na badge. --}}
                    @elseif ($product->isLowStock())
                        <span class="badge bg-warning text-dark">Low Stock</span>
                    {{-- Sapat ang stock: berdeng badge. --}}
                    @else
                        <span class="badge bg-success">In Stock</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ADJUST STOCK: form para sa Stock In o Stock Out. --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5>Adjust Stock</h5>
        {{-- POST papunta sa route na products.stock, ang tatawag sa adjustStock() method ng controller. align-items-start = para hindi masira ang pagkaka-align kapag may error message. --}}
        <form method="POST" action="{{ route('products.stock', $product) }}" class="row g-2 align-items-start">
            {{-- CSRF token. --}}
            @csrf
            {{-- URI NG TRANSACTION: in (dagdag) o out (bawas). --}}
            <div class="col-md-3">
                <select name="type" class="form-select @error('type') is-invalid @enderror">
                    {{-- Ang old('type') ay nagpapanatili ng napili kapag may validation error. --}}
                    <option value="in" @selected(old('type') === 'in')>Stock In (+)</option>
                    <option value="out" @selected(old('type') === 'out')>Stock Out (−)</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            {{-- QUANTITY: ilang piraso ang idadagdag o ibabawas. min="1" = hindi pwedeng zero o negatibo. --}}
            <div class="col-md-3">
                <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="Quantity"
                       class="form-control @error('quantity') is-invalid @enderror">
                @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            {{-- REFERENCE: opsyonal na dokumento (hal. purchase order PO-1001). --}}
            <div class="col-md-4">
                <input type="text" name="reference" value="{{ old('reference') }}" placeholder="Reference (e.g. PO-1001)"
                       class="form-control @error('reference') is-invalid @enderror">
                @error('reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            {{-- UPDATE BUTTON: isusumite ang form. w-100 = buong lapad ng kolum. --}}
            <div class="col-md-2">
                <button type="submit" class="btn btn-theme w-100">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- KASAYSAYAN NG STOCK: ang 10 pinakabagong transaction ng produkto. --}}
<h5>Recent Transactions</h5>
<div class="table-responsive">
    {{-- table-sm = mas siksik na table. --}}
    <table class="table table-sm bg-white shadow-sm">
        <thead><tr><th>Date</th><th>Type</th><th>Qty</th><th>Reference</th><th>By</th></tr></thead>
        <tbody>
            {{-- Paulit-ulit para sa bawat transaction. Kapag walang laman, ang @empty ang ipapakita. --}}
            @forelse ($transactions as $t)
                <tr>
                    {{-- Petsa at oras ng transaction, naka-format (hal. Nov 18, 2026 11:30 AM). --}}
                    <td>{{ $t->created_at->format('M d, Y h:i A') }}</td>
                    <td>
                        {{-- Berdeng "IN" para sa pumasok na stock, pulang "OUT" para sa lumabas. --}}
                        @if ($t->type === 'in')
                            <span class="badge bg-success">IN</span>
                        @else
                            <span class="badge bg-danger">OUT</span>
                        @endif
                    </td>
                    {{-- Ilang piraso ang pumasok o lumabas. --}}
                    <td>{{ $t->quantity }}</td>
                    {{-- Reference document; "—" kung wala. --}}
                    <td>{{ $t->reference ?? '—' }}</td>
                    {{-- Pangalan ng user na gumawa ng transaction (relationship user() ng InventoryTransaction); "—" kung wala. --}}
                    <td>{{ $t->user->name ?? '—' }}</td>
                </tr>
            {{-- Kapag walang transaction pa. --}}
            @empty
                <tr><td colspan="5" class="text-center text-muted">No transactions yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{-- Dulo ng content section. --}}
@endsection