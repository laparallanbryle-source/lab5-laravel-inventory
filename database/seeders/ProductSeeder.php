<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Lumber
            ['name' => 'Coco Lumber 2x2x10',        'sku' => 'LUM-001', 'category' => 'Lumber',     'quantity' => 120, 'reorder_level' => 30, 'unit_price' => 185.00, 'supplier' => 'Davao Timber Supply'],
            ['name' => 'Marine Plywood 1/2" 4x8',   'sku' => 'LUM-002', 'category' => 'Lumber',     'quantity' => 8,   'reorder_level' => 15, 'unit_price' => 980.00, 'supplier' => 'Davao Timber Supply'],
            ['name' => 'Ordinary Plywood 1/4" 4x8', 'sku' => 'LUM-003', 'category' => 'Lumber',     'quantity' => 45,  'reorder_level' => 15, 'unit_price' => 420.00, 'supplier' => 'Davao Timber Supply'],

            // Hardware
            ['name' => 'Common Nails 3" (kg)',      'sku' => 'HRD-001', 'category' => 'Hardware',   'quantity' => 200, 'reorder_level' => 50, 'unit_price' => 85.00,  'supplier' => 'Metro Hardware Trading'],
            ['name' => 'Concrete Nails 2" (kg)',    'sku' => 'HRD-002', 'category' => 'Hardware',   'quantity' => 6,   'reorder_level' => 20, 'unit_price' => 95.00,  'supplier' => 'Metro Hardware Trading'],
            ['name' => 'GI Wire #16 (kg)',          'sku' => 'HRD-003', 'category' => 'Hardware',   'quantity' => 0,   'reorder_level' => 10, 'unit_price' => 110.00, 'supplier' => 'Metro Hardware Trading'],

            // Tools
            ['name' => 'Claw Hammer 16oz',          'sku' => 'TLS-001', 'category' => 'Tools',      'quantity' => 35,  'reorder_level' => 10, 'unit_price' => 250.00, 'supplier' => 'ToolPro Philippines'],
            ['name' => 'Hand Saw 22"',              'sku' => 'TLS-002', 'category' => 'Tools',      'quantity' => 4,   'reorder_level' => 8,  'unit_price' => 320.00, 'supplier' => 'ToolPro Philippines'],
            ['name' => 'Steel Measuring Tape 5m',   'sku' => 'TLS-003', 'category' => 'Tools',      'quantity' => 60,  'reorder_level' => 15, 'unit_price' => 140.00, 'supplier' => 'ToolPro Philippines'],

            // Electrical
            ['name' => 'THHN Wire #12 (150m roll)', 'sku' => 'ELC-001', 'category' => 'Electrical', 'quantity' => 18,  'reorder_level' => 10, 'unit_price' => 3200.00, 'supplier' => 'Bright Electrical Corp'],
            ['name' => 'Circuit Breaker 20A',       'sku' => 'ELC-002', 'category' => 'Electrical', 'quantity' => 5,   'reorder_level' => 12, 'unit_price' => 280.00, 'supplier' => 'Bright Electrical Corp'],
            ['name' => 'LED Bulb 9W',               'sku' => 'ELC-003', 'category' => 'Electrical', 'quantity' => 150, 'reorder_level' => 40, 'unit_price' => 75.00,  'supplier' => 'Bright Electrical Corp'],

            // Plumbing
            ['name' => 'PVC Pipe 1/2" x 3m',        'sku' => 'PLB-001', 'category' => 'Plumbing',   'quantity' => 80,  'reorder_level' => 25, 'unit_price' => 95.00,  'supplier' => 'AquaFlow Trading'],
            ['name' => 'PVC Elbow 1/2"',            'sku' => 'PLB-002', 'category' => 'Plumbing',   'quantity' => 9,   'reorder_level' => 30, 'unit_price' => 12.00,  'supplier' => 'AquaFlow Trading'],
            ['name' => 'Gate Valve 3/4"',           'sku' => 'PLB-003', 'category' => 'Plumbing',   'quantity' => 22,  'reorder_level' => 8,  'unit_price' => 285.00, 'supplier' => 'AquaFlow Trading'],

            // Paint
            ['name' => 'Latex Paint White 4L',      'sku' => 'PNT-001', 'category' => 'Paint',      'quantity' => 40,  'reorder_level' => 12, 'unit_price' => 780.00, 'supplier' => 'ColorMax Paints'],
            ['name' => 'Enamel Paint Black 1L',     'sku' => 'PNT-002', 'category' => 'Paint',      'quantity' => 7,   'reorder_level' => 10, 'unit_price' => 340.00, 'supplier' => 'ColorMax Paints'],
            ['name' => 'Paint Brush 3"',            'sku' => 'PNT-003', 'category' => 'Paint',      'quantity' => 0,   'reorder_level' => 15, 'unit_price' => 65.00,  'supplier' => 'ColorMax Paints'],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(['sku' => $item['sku']], $item);
        }
    }
}