{{-- Ginagamit ang master layout (navbar, footer, Bootstrap). --}}
@extends('layouts.inventory')

{{-- Pamagat sa browser tab. --}}
@section('title', 'Products')

{{-- Simula ng laman na ilalagay sa content area ng layout. --}}
@section('content')

{{-- HIGHLIGHT: function na nagpapakulay (dilaw/lila) sa hinanap na salita. --}}
@php
    $highlight = function ($text) {
        // Hinanap na salita ng user.
        $q = request('search');
        // e() = ine-escape ang HTML para ligtas laban sa XSS (hindi tatakbo ang kahina-hinalang code).
        $safe = e($text);
        // Kung walang hinanap, ibalik ang text nang walang highlight.
        if (!$q) return $safe;
        // Ibalot ang tugma sa <mark> (hindi sensitibo sa laki ng letra). preg_quote = para ituring na ordinaryong text ang mga espesyal na simbolo.
        return preg_replace('/(' . preg_quote(e($q), '/') . ')/i', '<mark>$1</mark>', $safe);
    };
@endphp

{{-- HEADER NG PAGE: pamagat sa kaliwa at mga button sa kanan. --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Products</h2>
    <div class="d-flex gap-2">
        {{-- Export CSV: ipinapasa ang kasalukuyang filters (request()->query()) kaya ang nakikita mo ang ie-export. --}}
        <a href="{{ route('products.export', request()->query()) }}" class="btn btn-outline-theme">Export CSV</a>
        {{-- Link papunta sa form ng pagdagdag ng product. --}}
        <a href="{{ route('products.create') }}" class="btn btn-theme">+ Add Product</a>
    </div>
</div>

{{-- SEARCH AT FILTER FORM. method="GET" = ang laman ay ilalagay sa URL (hal. ?search=PVC), kaya madaling i-bookmark at gumagana ang pagination. --}}
<form method="GET" action="{{ route('products.index') }}" class="row g-2 mb-3">
    {{-- SEARCH: 4 sa 12 kolum. Ang value ay ang dating tinype para hindi mawala pagkatapos mag-filter. --}}
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or SKU...">
    </div>
    {{-- CATEGORY DROPDOWN: 2 sa 12 kolum. --}}
    <div class="col-md-2">
        <select name="category" class="form-select">
            <option value="">All Categories</option>
            @foreach ($categories as $cat)
                {{-- @selected = ilalagay ang "selected" kung ito ang kasalukuyang piniling category. --}}
                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    {{-- STATUS DROPDOWN: in, low, o out. Ang value ay tumutugma sa filteredQuery() ng controller. --}}
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="in" @selected(request('status') === 'in')>In Stock</option>
            <option value="low" @selected(request('status') === 'low')>Low Stock</option>
            <option value="out" @selected(request('status') === 'out')>Out of Stock</option>
        </select>
    </div>
    {{-- MINIMUM NA PRESYO: type="number" step="0.01" = tumatanggap ng sentimo. --}}
    <div class="col-6 col-md-1">
        <input type="number" step="0.01" min="0" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="Min ₱">
    </div>
    {{-- MAXIMUM NA PRESYO. --}}
    <div class="col-6 col-md-1">
        <input type="number" step="0.01" min="0" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="Max ₱">
    </div>
    {{-- MGA BUTTON: Filter at Reset. --}}
    <div class="col-md-2 d-flex gap-2">
        <button class="btn btn-outline-theme w-100" type="submit">Filter</button>
        {{-- Reset: bumabalik sa /products na walang filter. --}}
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

{{-- Ilan ang nahanap sa kabuuan (total() = lahat ng tugma sa lahat ng page). --}}
<p class="text-muted small mb-2">{{ $products->total() }} product(s) found</p>

{{-- table-responsive = kapag masikip ang screen (phone), pwedeng i-scroll pahalang ang table. --}}
<div class="table-responsive">
    <table class="table table-striped table-hover align-middle shadow-sm bg-white">
        {{-- Ulo ng table. Purple ito dahil sa custom.css. --}}
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
            {{-- @forelse = parang foreach, pero may @empty para kapag walang laman. --}}
            @forelse ($products as $product)
                {{-- Kapag mababa o wala nang stock, nilalagyan ng class na "row-alert" (dilaw na background). --}}
                <tr class="{{ $product->isLowStock() || $product->isOutOfStock() ? 'row-alert' : '' }}">
                    {{-- {!! !!} = hindi ine-escape ang HTML dahil <mark> ang ilalagay. Ligtas ito dahil ine-escape muna ng $highlight ang text. --}}
                    <td>{!! $highlight($product->sku) !!}</td>
                    <td>{!! $highlight($product->name) !!}</td>
                    <td>{{ $product->category }}</td>
                    <td class="text-end">{{ $product->quantity }}</td>
                    {{-- number_format(..., 2) = may kuwit at 2 decimal places. --}}
                    <td class="text-end">₱{{ number_format($product->unit_price, 2) }}</td>
                    <td>
                        {{-- Pula kung wala nang stock, dilaw kung mababa, berde kung sapat. --}}
                        @if ($product->isOutOfStock())
                            <span class="badge bg-danger">Out of Stock</span>
                        @elseif ($product->isLowStock())
                            <span class="badge bg-warning text-dark">Low Stock</span>
                        @else
                            <span class="badge bg-success">In Stock</span>
                        @endif
                    </td>
                    {{-- text-nowrap = hindi hahatiin sa dalawang linya ang mga button. --}}
                    <td class="text-end text-nowrap">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-theme">Edit</a>
                        {{-- Delete form: POST/DELETE ang pagbura kaya form, hindi link. onsubmit = magtatanong muna ng confirm. --}}
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete {{ $product->name }}?');">
                            {{-- CSRF token para tanggapin ng Laravel ang form. --}}
                            @csrf
                            {{-- Ginagawang DELETE ang request (POST lang ang kaya ng browser form). --}}
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                {{-- Kapag walang produkto: isang row na sumasakop sa 7 column. --}}
                <tr><td colspan="7" class="text-center text-muted py-4">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- PAGINATION: mga link ng page (1, 2, ...), 10 produkto kada page, at kasama ang mga filter. --}}
{{ $products->links('pagination::bootstrap-5') }}
{{-- Dulo ng content section. --}}
@endsection