<?php

// Nasa folder app/Http/Controllers ang class na ito.
namespace App\Http\Controllers;

// Ang Product model, para makuha ang data mula sa "products" table.
use App\Models\Product;
// Ang DB facade, ginagamit para sa raw SQL expression gaya ng SUM at COUNT.
use Illuminate\Support\Facades\DB;

// CONTROLLER ng reports: gumagawa ng inventory report page at CSV export.
class ReportController extends Controller
{
    // Tinatawag kapag binuksan ang /reports.
    public function index()
    {
        // Kabuuang bilang ng lahat ng produkto.
        $totalProducts = Product::count();
        // Kabuuang halaga ng inventory: SUM ng (quantity x unit_price) ng bawat produkto.
        $totalValue    = Product::sum(DB::raw('quantity * unit_price'));

        // Buod kada category: ilang produkto, kabuuang quantity, at kabuuang halaga.
        $categorySummary = Product::select(
                'category',                                                    // ang category
                DB::raw('COUNT(*) as items'),                                  // bilang ng produkto sa category
                DB::raw('SUM(quantity) as total_qty'),                         // kabuuang quantity sa category
                DB::raw('SUM(quantity * unit_price) as total_value')           // kabuuang halaga sa category
            )
            // Pagsama-samahin ang mga row ayon sa category.
            ->groupBy('category')
            // Ayusin ayon sa pangalan ng category.
            ->orderBy('category')
            // Kunin ang resulta bilang collection.
            ->get();

        // Top 5 produkto ayon sa kabuuang halaga. Kinukuha lahat, tapos inaayos mula pinakamataas (sortByDesc)...
        // gamit ang total_value accessor ng Product model (quantity x unit_price), at kinukuha ang unang 5 (take).
        $topProducts = Product::all()->sortByDesc('total_value')->take(5);

        // Mga produktong nasa o mas mababa sa reorder level (kasama ang out of stock).
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'reorder_level')
            // Pinakakaunti ang stock ang mauuna.
            ->orderBy('quantity')
            // Kunin ang lahat ng tugma.
            ->get();

        // Ipakita ang view na reports/index.blade.php at ipasa ang mga variable.
        return view('reports.index', compact(
            'totalProducts', 'totalValue', 'categorySummary', 'topProducts', 'lowStockProducts'
        ));
    }

    // Tinatawag kapag pinindot ang Export CSV (/reports/export).
    public function export()
    {
        // Kunin ang lahat ng produkto, nakaayos ayon sa pangalan.
        $products = Product::orderBy('name')->get();

        // streamDownload = ipapadala ang file sa browser habang ginagawa (hindi na ise-save sa server).
        return response()->streamDownload(function () use ($products) {
            // Binubuksan ang "php://output" para direktang isulat ang CSV sa response.
            $out = fopen('php://output', 'w');
            // Unang linya ng CSV: ang mga pamagat ng column.
            fputcsv($out, ['SKU', 'Name', 'Category', 'Quantity', 'Reorder Level', 'Unit Price', 'Supplier', 'Status', 'Total Value']);
            // Para sa bawat produkto...
            foreach ($products as $p) {
                // ...magsulat ng isang linya sa CSV. Ang fputcsv ang bahala sa kuwit at panipi.
                fputcsv($out, [
                    $p->sku, $p->name, $p->category, $p->quantity, $p->reorder_level,
                    // Ang status at total_value ay galing sa accessors ng Product model.
                    $p->unit_price, $p->supplier, $p->status, $p->total_value,
                ]);
            }
            // Isara ang output stream.
            fclose($out);
        // Pangalan ng file: may petsa ngayon (hal. inventory_report_2026-11-18.csv), at uri ng file ay CSV.
        }, 'inventory_report_' . date('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }
}