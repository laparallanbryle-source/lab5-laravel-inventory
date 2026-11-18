{{-- Ginagamit ang master layout (navbar, footer, Bootstrap). Ang laman ng page na ito ay ilalagay sa content area ng layout. --}}
@extends('layouts.inventory')

{{-- Pamagat sa browser tab: "Dashboard - Hardware IMS". --}}
@section('title', 'Dashboard')

{{-- Simula ng laman na ilalagay sa content area ng layout. --}}
@section('content')
{{-- Pamagat ng page. mb-4 = espasyo sa ibaba. --}}
<h2 class="mb-4">Dashboard</h2>

{{-- STATISTICS CARDS: apat na kahon na may kabuuang bilang. g-3 = espasyo sa pagitan, mb-4 = espasyo sa ibaba. --}}
<div class="row g-3 mb-4">
    {{-- col-6 = dalawa kada hilera sa phone; col-md-3 = apat kada hilera sa mas malaking screen. --}}
    <div class="col-6 col-md-3">
        {{-- Card na purple (#6D57D6, ang kulay mula sa Lab 4). text-white = puti ang text. --}}
        <div class="card text-white shadow-sm" style="background-color: #6D57D6;">
            <div class="card-body">
                <h6 class="card-title">Total Products</h6>
                {{-- Kabuuang bilang ng produkto, galing sa $totalProducts na ipinasa ng DashboardController. --}}
                <h3 class="mb-0">{{ $totalProducts }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        {{-- Card na asul-purple (#586BD6) para sa Low Stock. --}}
        <div class="card text-white shadow-sm" style="background-color: #586BD6;">
            <div class="card-body">
                <h6 class="card-title">Low Stock</h6>
                {{-- Bilang ng produktong mababa na ang stock. --}}
                <h3 class="mb-0">{{ $lowStockCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        {{-- Card na lila (#A372D6) para sa Out of Stock. --}}
        <div class="card text-white shadow-sm" style="background-color: #A372D6;">
            <div class="card-body">
                <h6 class="card-title">Out of Stock</h6>
                {{-- Bilang ng produktong wala nang stock. --}}
                <h3 class="mb-0">{{ $outOfStockCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        {{-- Card na rosas-lila (#C161D6) para sa Inventory Value. --}}
        <div class="card text-white shadow-sm" style="background-color: #C161D6;">
            <div class="card-body">
                <h6 class="card-title">Inventory Value</h6>
                {{-- Kabuuang halaga ng inventory, may kuwit at 2 decimal places (number_format). --}}
                <h3 class="mb-0">₱{{ number_format($inventoryValue, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- LOW STOCK ALERT: lalabas lang kung may kahit isang produktong nasa o mas mababa sa reorder level. --}}
@if ($lowStockProducts->count() > 0)
    {{-- alert-warning = dilaw na kahon. alert-low-stock = may makapal na pink-purple na linya sa kaliwa (custom.css). --}}
    <div class="alert alert-warning alert-low-stock">
        <strong>⚠ Low Stock Alert:</strong>
        {{-- Ilan ang kabuuang produktong kailangang i-reorder. --}}
        {{ $lowStockProducts->count() }} product(s) are at or below their reorder level.
        {{-- Listahan ng mga apektadong produkto. mb-0 mt-2 = walang espasyo sa ibaba, may espasyo sa itaas. --}}
        <ul class="mb-0 mt-2">
            {{-- Limang produkto lang ang ipapakita (take(5)) para hindi humaba ang alert. --}}
            @foreach ($lowStockProducts->take(5) as $p)
                <li>
                    {{-- Link papunta sa detalye ng produkto para madaling mag-Stock In. --}}
                    <a href="{{ route('products.show', $p) }}">{{ $p->name }}</a>
                    {{-- Ilan na lang ang natitira at kung kailan ito dapat i-reorder. --}}
                    — {{ $p->quantity }} left (reorder at {{ $p->reorder_level }})
                </li>
            @endforeach
        </ul>
        {{-- Kung higit sa 5 ang apektado, sabihin kung ilan pa ang hindi nakalista. --}}
        @if ($lowStockProducts->count() > 5)
            <small>and {{ $lowStockProducts->count() - 5 }} more...</small>
        @endif
    </div>
@endif

{{-- MGA CHART: dalawang card na magkatabi sa malaking screen. --}}
<div class="row g-3 mb-4">
    {{-- Bar chart: 7 sa 12 kolum (mas malawak). --}}
    <div class="col-md-7">
        <div class="card shadow-sm p-3">
            <h6>Inventory Value by Category</h6>
            {{-- Ang canvas ang pinagguguhitan ng Chart.js. Ang id ay hinahanap ng JavaScript sa ibaba. --}}
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    {{-- Doughnut chart: 5 sa 12 kolum. --}}
    <div class="col-md-5">
        <div class="card shadow-sm p-3">
            <h6>Stock Status Distribution</h6>
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

{{-- LISTAHAN NG KAKADAGDAG NA PRODUKTO --}}
<h5>Recently Added Products</h5>
<div class="table-responsive">
    <table class="table table-striped bg-white shadow-sm">
        <thead>
            <tr><th>SKU</th><th>Name</th><th>Category</th><th class="text-end">Qty</th><th>Added</th></tr>
        </thead>
        <tbody>
            {{-- Para sa bawat isa sa 5 pinakabagong produkto. Ang @empty ay para kung wala pang produkto. --}}
            @forelse ($recentProducts as $p)
                <tr>
                    <td>{{ $p->sku }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->category }}</td>
                    <td class="text-end">{{ $p->quantity }}</td>
                    {{-- diffForHumans = "5 minutes ago", "2 days ago" at iba pa, imbes na buong petsa. --}}
                    <td>{{ $p->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                {{-- Isang row na sumasakop sa lahat ng 5 column kung walang produkto. --}}
                <tr><td colspan="5" class="text-center text-muted">No products yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{-- Dulo ng content section. --}}
@endsection

{{-- Ang @push ay naglalagay ng mga script sa @stack('scripts') sa dulo ng master layout (pagkatapos ma-load ang Bootstrap). --}}
@push('scripts')
{{-- Ikinakabit ang Chart.js library (bersyon 4.4.3) galing sa internet (CDN). --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    // Mga pangalan ng category (hal. Lumber, Hardware) na galing sa database, ginawang JSON para magamit ng JavaScript.
    const categoryLabels = @json($categorySummary->pluck('category'));
    // Kabuuang halaga ng bawat category (quantity x presyo), parehong pagkakasunod sa itaas.
    const categoryValues = @json($categorySummary->pluck('total_value'));

    // Gumagawa ng BAR CHART sa canvas na may id na categoryChart.
    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',                       // uri ng chart: bar
        data: {
            labels: categoryLabels,        // mga label sa x-axis (mga category)
            datasets: [{
                label: 'Total Value (₱)',  // pamagat ng dataset sa legend
                data: categoryValues,      // mga numero ng bawat bar
                // Mga kulay ng bar: ang purple palette mula sa Lab 4 at dalawang dagdag.
                backgroundColor: ['#6D57D6', '#586BD6', '#A372D6', '#C161D6', '#8E7CE0', '#B59AE8']
            }]
        },
        options: { responsive: true }      // lumiliit o lumalaki ang chart ayon sa lapad ng lalagyan nito
    });

    // Gumagawa ng DOUGHNUT CHART sa canvas na may id na statusChart.
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',                  // uri ng chart: doughnut (bilog na may butas sa gitna)
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],   // tatlong hiwa ng doughnut
            datasets: [{
                // Mga bilang na ipinasa ng controller (inilalagay ni Blade ang mga numero bago tumakbo ang JavaScript).
                data: [{{ $inStockCount }}, {{ $lowStockCount }}, {{ $outOfStockCount }}],
                // Berde para sa In Stock, dilaw para sa Low Stock, pula para sa Out of Stock.
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: { responsive: true }      // responsive din para sa mobile
    });
</script>
@endpush