<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts   = Product::count();
        $outOfStockCount = Product::where('quantity', 0)->count();
        $lowStockCount   = Product::where('quantity', '>', 0)
                                  ->whereColumn('quantity', '<=', 'reorder_level')
                                  ->count();
        $inStockCount    = $totalProducts - $lowStockCount - $outOfStockCount;
        $inventoryValue  = Product::sum(DB::raw('quantity * unit_price'));

        $lowStockProducts = Product::whereColumn('quantity', '<=', 'reorder_level')
                                   ->orderBy('quantity')
                                   ->get();

        $recentProducts = Product::latest()->take(5)->get();

        $categorySummary = Product::select('category', DB::raw('SUM(quantity * unit_price) as total_value'))
                                  ->groupBy('category')
                                  ->orderBy('category')
                                  ->get();

        return view('dashboard', compact(
            'totalProducts', 'lowStockCount', 'outOfStockCount', 'inStockCount',
            'inventoryValue', 'lowStockProducts', 'recentProducts', 'categorySummary'
        ));
    }
}