<?php

// Nasa folder database/seeders ang class na ito.
namespace Database\Seeders;

// Ang Product model, para makapag-save ng data sa "products" table.
use App\Models\Product;
// Ang base class ng lahat ng seeder.
use Illuminate\Database\Seeder;

// SEEDER = naglalagay ng sample data sa database para may laman ang system habang dine-develop at tine-test.
class ProductSeeder extends Seeder
{
    // run() = tumatakbo kapag "php artisan db:seed".
    public function run(): void
    {
        // Listahan (array) ng mga produkto. Bawat produkto ay isang array na may key => value.
        $products = [
            // ===== LUMBER =====
            // Coco lumber: 120 ang stock, reorder level ay 30, kaya In Stock.
            ['name' => 'Coco Lumber 2x2x10',        'sku' => 'LUM-001', 'category' => 'Lumber',     'quantity' => 120, 'reorder_level' => 30, 'unit_price' => 185.00, 'supplier' => 'Davao Timber Supply'],
            // Marine plywood: 8 lang ang stock at reorder level ay 15, kaya LOW STOCK.
            ['name' => 'Marine Plywood 1/2" 4x8',   'sku' => 'LUM-002', 'category' => 'Lumber',     'quantity' => 8,   'reorder_level' => 15, 'unit_price' => 980.00, 'supplier' => 'Davao Timber Supply'],
            // Ordinary plywood: 45 ang stock, mas mataas sa reorder level na 15, kaya In Stock.
            ['name' => 'Ordinary Plywood 1/4" 4x8', 'sku' => 'LUM-003', 'category' => 'Lumber',     'quantity' => 45,  'reorder_level' => 15, 'unit_price' => 420.00, 'supplier' => 'Davao Timber Supply'],

            // ===== HARDWARE =====
            // Common nails: 200 ang stock, In Stock.
            ['name' => 'Common Nails 3" (kg)',      'sku' => 'HRD-001', 'category' => 'Hardware',   'quantity' => 200, 'reorder_level' => 50, 'unit_price' => 85.00,  'supplier' => 'Metro Hardware Trading'],
            // Concrete nails: 6 lang ang stock at reorder level ay 20, kaya LOW STOCK.
            ['name' => 'Concrete Nails 2" (kg)',    'sku' => 'HRD-002', 'category' => 'Hardware',   'quantity' => 6,   'reorder_level' => 20, 'unit_price' => 95.00,  'supplier' => 'Metro Hardware Trading'],
            // GI wire: quantity ay 0, kaya OUT OF STOCK.
            ['name' => 'GI Wire #16 (kg)',          'sku' => 'HRD-003', 'category' => 'Hardware',   'quantity' => 0,   'reorder_level' => 10, 'unit_price' => 110.00, 'supplier' => 'Metro Hardware Trading'],

            // ===== TOOLS =====
            // Claw hammer: 35 ang stock, In Stock.
            ['name' => 'Claw Hammer 16oz',          'sku' => 'TLS-001', 'category' => 'Tools',      'quantity' => 35,  'reorder_level' => 10, 'unit_price' => 250.00, 'supplier' => 'ToolPro Philippines'],
            // Hand saw: 4 lang ang stock at reorder level ay 8, kaya LOW STOCK.
            ['name' => 'Hand Saw 22"',              'sku' => 'TLS-002', 'category' => 'Tools',      'quantity' => 4,   'reorder_level' => 8,  'unit_price' => 320.00, 'supplier' => 'ToolPro Philippines'],
            // Measuring tape: 60 ang stock, In Stock.
            ['name' => 'Steel Measuring Tape 5m',   'sku' => 'TLS-003', 'category' => 'Tools',      'quantity' => 60,  'reorder_level' => 15, 'unit_price' => 140.00, 'supplier' => 'ToolPro Philippines'],

            // ===== ELECTRICAL =====
            // THHN wire: 18 ang stock, mas mataas sa reorder level na 10, kaya In Stock. (Pinakamahal kada piraso: 3200.00)
            ['name' => 'THHN Wire #12 (150m roll)', 'sku' => 'ELC-001', 'category' => 'Electrical', 'quantity' => 18,  'reorder_level' => 10, 'unit_price' => 3200.00, 'supplier' => 'Bright Electrical Corp'],
            // Circuit breaker: 5 lang ang stock at reorder level ay 12, kaya LOW STOCK.
            ['name' => 'Circuit Breaker 20A',       'sku' => 'ELC-002', 'category' => 'Electrical', 'quantity' => 5,   'reorder_level' => 12, 'unit_price' => 280.00, 'supplier' => 'Bright Electrical Corp'],
            // LED bulb: 150 ang stock, In Stock.
            ['name' => 'LED Bulb 9W',               'sku' => 'ELC-003', 'category' => 'Electrical', 'quantity' => 150, 'reorder_level' => 40, 'unit_price' => 75.00,  'supplier' => 'Bright Electrical Corp'],

            // ===== PLUMBING =====
            // PVC pipe: 80 ang stock, In Stock.
            ['name' => 'PVC Pipe 1/2" x 3m',        'sku' => 'PLB-001', 'category' => 'Plumbing',   'quantity' => 80,  'reorder_level' => 25, 'unit_price' => 95.00,  'supplier' => 'AquaFlow Trading'],
            // PVC elbow: 9 lang ang stock at reorder level ay 30, kaya LOW STOCK.
            ['name' => 'PVC Elbow 1/2"',            'sku' => 'PLB-002', 'category' => 'Plumbing',   'quantity' => 9,   'reorder_level' => 30, 'unit_price' => 12.00,  'supplier' => 'AquaFlow Trading'],
            // Gate valve: 22 ang stock, In Stock.
            ['name' => 'Gate Valve 3/4"',           'sku' => 'PLB-003', 'category' => 'Plumbing',   'quantity' => 22,  'reorder_level' => 8,  'unit_price' => 285.00, 'supplier' => 'AquaFlow Trading'],

            // ===== PAINT =====
            // Latex paint: 40 ang stock, In Stock.
            ['name' => 'Latex Paint White 4L',      'sku' => 'PNT-001', 'category' => 'Paint',      'quantity' => 40,  'reorder_level' => 12, 'unit_price' => 780.00, 'supplier' => 'ColorMax Paints'],
            // Enamel paint: 7 lang ang stock at reorder level ay 10, kaya LOW STOCK.
            ['name' => 'Enamel Paint Black 1L',     'sku' => 'PNT-002', 'category' => 'Paint',      'quantity' => 7,   'reorder_level' => 10, 'unit_price' => 340.00, 'supplier' => 'ColorMax Paints'],
            // Paint brush: quantity ay 0, kaya OUT OF STOCK.
            ['name' => 'Paint Brush 3"',            'sku' => 'PNT-003', 'category' => 'Paint',      'quantity' => 0,   'reorder_level' => 15, 'unit_price' => 65.00,  'supplier' => 'ColorMax Paints'],
        ];

        // Para sa bawat produkto sa listahan...
        foreach ($products as $item) {
            // updateOrCreate: hanapin ang produkto ayon sa sku. Kapag nandoon na, i-update. Kapag wala pa, gumawa ng bago.
            // Ito ang dahilan kung bakit hindi nadodoble ang data kahit ilang beses i-run ang db:seed.
            Product::updateOrCreate(['sku' => $item['sku']], $item);
        }
    }
}