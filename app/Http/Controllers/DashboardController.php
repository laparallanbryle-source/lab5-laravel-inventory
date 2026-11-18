<?php

// Nasa folder app/Http/Controllers ang class na ito.
namespace App\Http\Controllers;

// Ang Product model, para makuha ang data mula sa "products" table.
use App\Models\Product;
// Ang DB facade, ginagamit para sa raw SQL expression gaya ng quantity * unit_price.
use Illuminate\Support\Facades\DB;

// CONTROLLER ng dashboard: kinukuha ang statistics sa database at ipinapasa sa dashboard view.
class DashboardController extends Controller
{
    // Tinatawag kapag binuksan ang /dashboard (ayon sa routes/web.php).
    public function index()
    {
        // Kabuuang bilang ng lahat ng produkto.
        $totalProducts   = Product::count();
        // Bilang ng produktong wala nang stock (quantity ay 0).
        $outOfStockCount = Product::where('quantity', 0)->count();
        // Bilang ng produktong mababa ang stock: may laman pa (mas malaki sa 0)...
        $lowStockCount   = Product::where('quantity', '>', 0)
                                  // ...pero hindi lalampas sa reorder_level (paghahambing ng dalawang column).
                                  ->whereColumn('quantity', '<=', 'reorder_level')
                                  // Bilangin ang mga tugma.
                                  ->count();
        // In stock = lahat ng produkto minus ang low stock at out of stock.
        $inStockCount    = $totalProducts - $lowStockCount - $outOfStockCount;
        // Kabuuang halaga ng inventory: ang SUM ng (quantity x unit_price) ng bawat produkto.
        $inventoryValue  = Product::sum(DB::raw('quantity * unit_price'));

        // Mga produktong nasa o mas mababa sa reorder level (kasama ang out of stock) para sa alert.
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'reorder_level')
                                   // Pinakakaunti ang stock ang mauuna sa listahan.
                                   ->orderBy('quantity')
                                   // Kunin ang lahat ng tugma bilang collection.
                                   ->get();

        // 5 pinakabagong idinagdag na produkto (latest() = ayon sa created_at, pinakabago muna).
        $recentProducts = Product::latest()->take(5)->get();

        // Para sa bar chart: pipiliin ang category at ang kabuuang halaga (quantity x price) kada category.
        $categorySummary = Product::select('category', DB::raw('SUM(quantity * unit_price) as total_value'))
                                  // Pagsama-samahin ayon sa category.
                                  ->groupBy('category')
                                  // Ayusin ayon sa pangalan ng category.
                                  ->orderBy('category')
                                  // Kunin ang resulta.
                                  ->get();

        // Ipakita ang view na dashboard.blade.php at ipasa ang lahat ng variable sa itaas.
        return view('dashboard', compact(
            'totalProducts', 'lowStockCount', 'outOfStockCount', 'inStockCount',
            'inventoryValue', 'lowStockProducts', 'recentProducts', 'categorySummary'
        ));
    }
}