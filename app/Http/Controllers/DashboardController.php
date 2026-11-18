<?php

// Nasa folder app/Http/Controllers ang class na ito.
namespace App\Http\Controllers;

// Ang Product model, para makuha ang data mula sa "products" table.
use App\Models\Product;
// Ang DB facade, para sa raw SQL expression gaya ng quantity * unit_price.
use Illuminate\Support\Facades\DB;

// CONTROLLER ng dashboard: kinukuha ang statistics at ipinapasa sa view o sa JavaScript (JSON).
class DashboardController extends Controller
{
    // Iisang lugar na kumukuha ng lahat ng statistics. Ginagamit ng index() at stats() para parehas ang numero.
    private function buildStats(): array
    {
        // Kabuuang bilang ng produkto.
        $totalProducts = Product::count();
        // Bilang ng produktong wala nang stock.
        $outOfStockCount = Product::where('quantity', 0)->count();
        // Bilang ng produktong mababa ang stock: may laman pa pero hindi lalampas sa reorder_level.
        $lowStockCount = Product::where('quantity', '>', 0)
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->count();
        // In stock = lahat minus ang low stock at out of stock.
        $inStockCount = $totalProducts - $lowStockCount - $outOfStockCount;
        // Kabuuang halaga ng inventory (quantity x unit_price). Ginagawang float para tamang numero sa JSON.
        $inventoryValue = (float) Product::sum(DB::raw('quantity * unit_price'));

        // Mga produktong nasa o mas mababa sa reorder level (kasama ang out of stock), pinakakaunti muna.
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'reorder_level')
            ->orderBy('quantity')
            ->get();

        // Kabuuang halaga kada category, para sa bar chart.
        $categorySummary = Product::select('category', DB::raw('SUM(quantity * unit_price) as total_value'))
            ->groupBy('category')
            ->orderBy('category')
            ->get();
        // Mga pangalan ng category (labels ng chart).
        $categoryLabels = $categorySummary->pluck('category')->values();
        // Mga halaga ng bawat category. Ginagawang float dahil string ang galing sa database.
        $categoryValues = $categorySummary->pluck('total_value')->map(fn ($v) => (float) $v)->values();

        // Top 5 produkto ayon sa halaga ng stock (gamit ang total_value accessor ng Product model).
        $topProducts = Product::all()->sortByDesc('total_value')->take(5)->values();
        // Mga pangalan ng top 5 (labels ng chart).
        $topLabels = $topProducts->pluck('name')->values();
        // Mga halaga ng top 5.
        $topValues = $topProducts->map(fn ($p) => (float) $p->total_value)->values();

        // Ibinabalik ang lahat bilang array na may pangalan (compact ang gumagawa nito).
        return compact(
            'totalProducts', 'lowStockCount', 'outOfStockCount', 'inStockCount', 'inventoryValue',
            'lowStockProducts', 'categoryLabels', 'categoryValues', 'topLabels', 'topValues'
        );
    }

    // Tinatawag kapag binuksan ang /dashboard.
    public function index()
    {
        // Kunin ang lahat ng statistics.
        $data = $this->buildStats();
        // Dagdag: 5 pinakabagong idinagdag na produkto (latest() = pinakabago muna).
        $data['recentProducts'] = Product::latest()->take(5)->get();

        // Ipakita ang dashboard.blade.php at ipasa ang lahat ng variable sa $data.
        return view('dashboard', $data);
    }

    // Tinatawag ng JavaScript tuwing 15 segundo (/dashboard/stats) para sa real-time update. JSON ang sagot, hindi page.
    public function stats()
    {
        // Kunin ang pinakabagong statistics mula sa database.
        $d = $this->buildStats();

        // Ibalik bilang JSON ang mga kailangan ng cards, charts, at alert.
        return response()->json([
            'totalProducts'   => $d['totalProducts'],
            'lowStockCount'   => $d['lowStockCount'],
            'outOfStockCount' => $d['outOfStockCount'],
            'inStockCount'    => $d['inStockCount'],
            'inventoryValue'  => $d['inventoryValue'],
            'categoryLabels'  => $d['categoryLabels'],
            'categoryValues'  => $d['categoryValues'],
            'topLabels'       => $d['topLabels'],
            'topValues'       => $d['topValues'],
            // Kabuuang bilang ng produktong kailangang i-reorder.
            'lowStockTotal'   => $d['lowStockProducts']->count(),
            // Unang 5 lang ang ililista sa alert, kasama ang link papunta sa detalye ng produkto.
            'lowStockList'    => $d['lowStockProducts']->take(5)->map(fn ($p) => [
                'name'          => $p->name,
                'quantity'      => $p->quantity,
                'reorder_level' => $p->reorder_level,
                'url'           => route('products.show', $p),
            ])->values(),
        ]);
    }
}