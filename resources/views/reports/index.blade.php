{{-- Ginagamit ang master layout. --}}
@extends('layouts.inventory')

{{-- Pamagat sa browser tab: "Inventory Report - Hardware IMS". --}}
@section('title', 'Inventory Report')

{{-- Simula ng laman na ilalagay sa content area. --}}
@section('content')
{{-- HEADER: pamagat sa kaliwa, mga button sa kanan. mb-2 = maliit na espasyo sa ibaba. --}}
<div class="d-flex justify-content-between align-items-center mb-2">
    <h2 class="mb-0">Inventory Report</h2>
    {{-- Mga button, magkatabi na may espasyo (gap-2). --}}
    <div class="d-flex gap-2">
        {{-- Print: tinatawag ang window.print() ng browser para buksan ang print dialog (pwede ring i-save bilang PDF). --}}
        <button onclick="window.print()" class="btn btn-outline-theme">Print</button>
        {{-- Export CSV: papunta sa /reports/export, ang tatawag sa export() ng ReportController para mag-download ng CSV. --}}
        <a href="{{ route('reports.export') }}" class="btn btn-theme">Export CSV</a>
    </div>
</div>
{{-- Petsa at oras kung kailan ginawa ang report. now() = kasalukuyang oras (Asia/Manila ayon sa .env). --}}
<p class="text-muted">Generated on {{ now()->format('M d, Y h:i A') }}</p>

{{-- BUOD NA CARDS: tatlong kahon, tig-iisang-katlo ng lapad (col-md-4). --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <h6>Total Products</h6>
            {{-- Kabuuang bilang ng produkto. --}}
            <h3 class="mb-0">{{ $totalProducts }}</h3>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <h6>Total Inventory Value</h6>
            {{-- Kabuuang halaga ng lahat ng stock. --}}
            <h3 class="mb-0">₱{{ number_format($totalValue, 2) }}</h3>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm"><div class="card-body">
            <h6>Items Needing Reorder</h6>
            {{-- Ilan ang produktong nasa o mas mababa sa reorder level. --}}
            <h3 class="mb-0">{{ $lowStockProducts->count() }}</h3>
        </div></div>
    </div>
</div>

{{-- TABLE 1: BUOD KADA CATEGORY --}}
<h5>Summary by Category</h5>
{{-- mb-4 = espasyo sa ibaba. table-responsive = pwedeng i-scroll pahalang sa phone. --}}
<div class="table-responsive mb-4">
    <table class="table table-striped bg-white shadow-sm">
        <thead><tr><th>Category</th><th class="text-end">Products</th><th class="text-end">Total Qty</th><th class="text-end">Total Value</th></tr></thead>
        <tbody>
            {{-- Isang row kada category. Ang items, total_qty, at total_value ay galing sa SQL (COUNT at SUM) ng ReportController. --}}
            @foreach ($categorySummary as $row)
                <tr>
                    <td>{{ $row->category }}</td>
                    {{-- Ilang produkto sa category. --}}
                    <td class="text-end">{{ $row->items }}</td>
                    {{-- Kabuuang dami ng stock sa category. --}}
                    <td class="text-end">{{ $row->total_qty }}</td>
                    {{-- Kabuuang halaga ng category. --}}
                    <td class="text-end">₱{{ number_format($row->total_value, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- TABLE 2: TOP 5 PRODUKTO AYON SA HALAGA --}}
<h5>Top 5 Products by Inventory Value</h5>
<div class="table-responsive mb-4">
    <table class="table table-striped bg-white shadow-sm">
        <thead><tr><th>Product</th><th class="text-end">Qty</th><th class="text-end">Unit Price</th><th class="text-end">Total Value</th></tr></thead>
        <tbody>
            {{-- Ang $topProducts ay nakaayos na mula pinakamataas ang halaga (sortByDesc sa controller). --}}
            @foreach ($topProducts as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td class="text-end">{{ $p->quantity }}</td>
                    <td class="text-end">₱{{ number_format($p->unit_price, 2) }}</td>
                    {{-- total_value ay accessor ng Product model: quantity x unit_price. --}}
                    <td class="text-end">₱{{ number_format($p->total_value, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- TABLE 3: MGA PRODUKTONG KAILANGANG I-REORDER --}}
<h5>Items at or Below Reorder Level</h5>
<div class="table-responsive">
    <table class="table table-striped bg-white shadow-sm">
        <thead><tr><th>SKU</th><th>Product</th><th class="text-end">Qty</th><th class="text-end">Reorder Level</th><th>Status</th></tr></thead>
        <tbody>
            {{-- Paulit-ulit para sa bawat produktong mababa ang stock. Ang @empty ay para kung wala. --}}
            @forelse ($lowStockProducts as $p)
                {{-- row-alert = dilaw na background (custom.css) para mapansin. --}}
                <tr class="row-alert">
                    <td>{{ $p->sku }}</td>
                    <td>{{ $p->name }}</td>
                    <td class="text-end">{{ $p->quantity }}</td>
                    <td class="text-end">{{ $p->reorder_level }}</td>
                    <td>
                        {{-- Pulang badge kung wala nang stock. --}}
                        @if ($p->isOutOfStock())
                            <span class="badge bg-danger">Out of Stock</span>
                        {{-- Dilaw na badge kung mababa lang ang stock. --}}
                        @else
                            <span class="badge bg-warning text-dark">Low Stock</span>
                        @endif
                    </td>
                </tr>
            {{-- Kung sapat ang stock ng lahat. --}}
            @empty
                <tr><td colspan="5" class="text-center text-muted">All products are sufficiently stocked.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{-- Dulo ng content section. --}}
@endsection