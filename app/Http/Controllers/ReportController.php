<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalValue    = Product::sum(DB::raw('quantity * unit_price'));

        $categorySummary = Product::select(
                'category',
                DB::raw('COUNT(*) as items'),
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(quantity * unit_price) as total_value')
            )
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        $topProducts = Product::all()->sortByDesc('total_value')->take(5);

        $lowStockProducts = Product::whereColumn('quantity', '<=', 'reorder_level')
            ->orderBy('quantity')
            ->get();

        return view('reports.index', compact(
            'totalProducts', 'totalValue', 'categorySummary', 'topProducts', 'lowStockProducts'
        ));
    }

    public function export()
    {
        $products = Product::orderBy('name')->get();

        return response()->streamDownload(function () use ($products) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['SKU', 'Name', 'Category', 'Quantity', 'Reorder Level', 'Unit Price', 'Supplier', 'Status', 'Total Value']);
            foreach ($products as $p) {
                fputcsv($out, [
                    $p->sku, $p->name, $p->category, $p->quantity, $p->reorder_level,
                    $p->unit_price, $p->supplier, $p->status, $p->total_value,
                ]);
            }
            fclose($out);
        }, 'inventory_report_' . date('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }
}