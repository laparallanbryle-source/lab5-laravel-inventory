{{-- Ginagamit ang master layout. --}}
@extends('layouts.inventory')

{{-- Pamagat sa browser tab. --}}
@section('title', 'Dashboard')

{{-- Simula ng laman na ilalagay sa content area. --}}
@section('content')

{{-- TOAST NOTIFICATION: maliit na kahon sa kanang itaas na lalabas kapag may nagbago sa inventory. position-fixed = nakabitin sa screen. --}}
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div id="updateToast" class="toast align-items-center text-white border-0" style="background-color: #6D57D6;" role="alert">
        <div class="d-flex">
            {{-- Dito ilalagay ng JavaScript ang mensahe. --}}
            <div class="toast-body" id="toastMessage">Update!</div>
            {{-- X button na nagsasara ng toast. --}}
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

{{-- HEADER: pamagat at maliit na tala kung kailan huling nag-update. --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Dashboard</h2>
    <small class="text-muted">Auto-refresh every 15 sec · Last updated: <span id="lastUpdated">{{ now()->format('h:i:s A') }}</span></small>
</div>

{{-- STATISTICS CARDS: apat na kahon. Ang bawat numero ay may id para ma-update ng JavaScript. --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-white shadow-sm" style="background-color: #6D57D6;">
            <div class="card-body">
                <h6 class="card-title">Total Products</h6>
                <h3 class="mb-0" id="statTotal">{{ $totalProducts }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-white shadow-sm" style="background-color: #586BD6;">
            <div class="card-body">
                <h6 class="card-title">Low Stock</h6>
                <h3 class="mb-0" id="statLow">{{ $lowStockCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-white shadow-sm" style="background-color: #A372D6;">
            <div class="card-body">
                <h6 class="card-title">Out of Stock</h6>
                <h3 class="mb-0" id="statOut">{{ $outOfStockCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-white shadow-sm" style="background-color: #C161D6;">
            <div class="card-body">
                <h6 class="card-title">Inventory Value</h6>
                <h3 class="mb-0" id="statValue">₱{{ number_format($inventoryValue, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- LOW STOCK ALERT: ang lalagyan (alertBox) ay laging nandito para mapalitan ng JavaScript ang laman. --}}
<div id="alertBox">
    {{-- Lalabas lang kung may kahit isang produktong nasa o mas mababa sa reorder level. --}}
    @if ($lowStockProducts->count() > 0)
        <div class="alert alert-warning alert-low-stock">
            <strong>⚠ Low Stock Alert:</strong>
            {{ $lowStockProducts->count() }} product(s) are at or below their reorder level.
            <ul class="mb-0 mt-2">
                {{-- Limang produkto lang ang ipapakita para hindi humaba ang alert. --}}
                @foreach ($lowStockProducts->take(5) as $p)
                    <li>
                        {{-- Link papunta sa detalye ng produkto para madaling mag-Stock In. --}}
                        <a href="{{ route('products.show', $p) }}">{{ $p->name }}</a>
                        — {{ $p->quantity }} left (reorder at {{ $p->reorder_level }})
                    </li>
                @endforeach
            </ul>
            {{-- Kung higit sa 5 ang apektado, sabihin kung ilan pa. --}}
            @if ($lowStockProducts->count() > 5)
                <small>and {{ $lowStockProducts->count() - 5 }} more...</small>
            @endif
        </div>
    @endif
</div>

{{-- MGA CHART: dalawa sa itaas na hilera, isa sa ibaba. --}}
<div class="row g-3 mb-3">
    {{-- Bar chart: halaga kada category. --}}
    <div class="col-md-7">
        <div class="card shadow-sm p-3">
            <h6>Inventory Value by Category</h6>
            {{-- Ang canvas ang pinagguguhitan ng Chart.js. Ang id ay hinahanap ng JavaScript sa ibaba. --}}
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    {{-- Doughnut chart: In Stock, Low Stock, Out of Stock. --}}
    <div class="col-md-5">
        <div class="card shadow-sm p-3">
            <h6>Stock Status Distribution</h6>
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>
{{-- Horizontal bar chart: Top 5 produkto ayon sa halaga ng stock. --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card shadow-sm p-3">
            <h6>Top 5 Products by Inventory Value</h6>
            <canvas id="topChart"></canvas>
        </div>
    </div>
</div>

{{-- LISTAHAN NG PINAKABAGONG PRODUKTO --}}
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
                    {{-- diffForHumans = "5 minutes ago", "2 days ago", at iba pa. --}}
                    <td>{{ $p->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">No products yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{-- Dulo ng content section. --}}
@endsection

{{-- Ang @push ay naglalagay ng mga script sa dulo ng master layout (pagkatapos ma-load ang Bootstrap). --}}
@push('scripts')
{{-- Chart.js library (bersyon 4.4.3) galing sa CDN. --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    // ===== MGA CHART (unang data galing sa database, ipinasa ng controller) =====

    // BAR CHART: halaga kada category. Ang @json ay ginagawang JavaScript array ang data ng PHP.
    const categoryChart = new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: @json($categoryLabels),          // mga pangalan ng category
            datasets: [{
                label: 'Total Value (₱)',            // pamagat ng dataset
                data: @json($categoryValues),        // halaga ng bawat bar
                backgroundColor: ['#6D57D6', '#586BD6', '#A372D6', '#C161D6', '#8E7CE0', '#B59AE8']   // purple palette ng Lab 4
            }]
        },
        options: { responsive: true }                // lumiliit o lumalaki ayon sa lapad ng lalagyan
    });

    // DOUGHNUT CHART: In Stock, Low Stock, Out of Stock.
    const statusChart = new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [{{ $inStockCount }}, {{ $lowStockCount }}, {{ $outOfStockCount }}],   // mga bilang mula sa controller
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']   // berde, dilaw, pula
            }]
        },
        options: { responsive: true }
    });

    // HORIZONTAL BAR CHART: Top 5 produkto. Ang indexAxis: 'y' ang nagpapahiga sa bar.
    const topChart = new Chart(document.getElementById('topChart'), {
        type: 'bar',
        data: {
            labels: @json($topLabels),
            datasets: [{
                label: 'Inventory Value (₱)',
                data: @json($topValues),
                backgroundColor: '#A372D6'
            }]
        },
        options: { indexAxis: 'y', responsive: true }
    });

    // ===== REAL-TIME UPDATE =====

    // Tinatandaan ang huling numero para malaman kung may nagbago.
    let last = { low: {{ $lowStockCount }}, out: {{ $outOfStockCount }}, total: {{ $totalProducts }} };

    // Ginagawang pera ang numero (hal. 1234.5 ay ₱1,234.50).
    const peso = (v) => '₱' + Number(v).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // Ine-escape ang text bago ilagay sa HTML para ligtas laban sa XSS.
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Ipinapakita ang toast notification na may mensahe.
    function showToast(message) {
        document.getElementById('toastMessage').textContent = message;
        bootstrap.Toast.getOrCreateInstance(document.getElementById('updateToast')).show();
    }

    // Binubuo ulit ang low stock alert mula sa bagong data.
    function renderAlert(data) {
        const box = document.getElementById('alertBox');
        // Kung wala nang produktong kailangang i-reorder, tanggalin ang alert.
        if (data.lowStockTotal === 0) { box.innerHTML = ''; return; }
        // Isang <li> kada produkto sa listahan.
        const items = data.lowStockList.map((p) =>
            `<li><a href="${p.url}">${escapeHtml(p.name)}</a> — ${p.quantity} left (reorder at ${p.reorder_level})</li>`
        ).join('');
        // Kung higit sa 5, sabihin kung ilan pa.
        const more = data.lowStockTotal > 5 ? `<small>and ${data.lowStockTotal - 5} more...</small>` : '';
        box.innerHTML = `<div class="alert alert-warning alert-low-stock"><strong>⚠ Low Stock Alert:</strong> ${data.lowStockTotal} product(s) are at or below their reorder level.<ul class="mb-0 mt-2">${items}</ul>${more}</div>`;
    }

    // Kumukuha ng bagong data sa server (async/await + fetch) at ina-update ang cards, charts, at alert.
    async function refreshDashboard() {
        try {
            // GET /dashboard/stats na JSON ang hinihingi. Awtomatikong kasama ang session cookie.
            const response = await fetch('{{ route("dashboard.stats") }}', { headers: { 'Accept': 'application/json' } });
            // Kung hindi OK ang sagot (hal. nag-expire ang login), huwag nang ituloy.
            if (!response.ok) return;
            // Gawing JavaScript object ang JSON.
            const data = await response.json();

            // I-update ang mga numero sa cards.
            document.getElementById('statTotal').textContent = data.totalProducts;
            document.getElementById('statLow').textContent = data.lowStockCount;
            document.getElementById('statOut').textContent = data.outOfStockCount;
            document.getElementById('statValue').textContent = peso(data.inventoryValue);

            // I-update ang bar chart ng category at ipaguhit ulit.
            categoryChart.data.labels = data.categoryLabels;
            categoryChart.data.datasets[0].data = data.categoryValues;
            categoryChart.update();

            // I-update ang doughnut chart.
            statusChart.data.datasets[0].data = [data.inStockCount, data.lowStockCount, data.outOfStockCount];
            statusChart.update();

            // I-update ang top 5 chart.
            topChart.data.labels = data.topLabels;
            topChart.data.datasets[0].data = data.topValues;
            topChart.update();

            // I-update ang low stock alert.
            renderAlert(data);

            // Ipakita kung anong oras huling nag-update.
            document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();

            // Kung may nagbago sa bilang, magpakita ng toast.
            const changes = [];
            if (data.lowStockCount !== last.low) changes.push('Low stock: ' + last.low + ' → ' + data.lowStockCount);
            if (data.outOfStockCount !== last.out) changes.push('Out of stock: ' + last.out + ' → ' + data.outOfStockCount);
            if (data.totalProducts !== last.total) changes.push('Total products: ' + last.total + ' → ' + data.totalProducts);
            if (changes.length > 0) showToast('Inventory updated. ' + changes.join(' | '));

            // Tandaan ang bagong numero para sa susunod na paghahambing.
            last = { low: data.lowStockCount, out: data.outOfStockCount, total: data.totalProducts };
        } catch (error) {
            // Kung may problema sa koneksyon, ilalabas lang sa console (F12) at susubukan ulit sa susunod.
            console.error('Dashboard refresh failed:', error);
        }
    }

    // Tatakbo ang refreshDashboard() tuwing 15,000 milliseconds (15 segundo).
    setInterval(refreshDashboard, 15000);
</script>
@endpush