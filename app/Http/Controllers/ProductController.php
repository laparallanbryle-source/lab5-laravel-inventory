<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Mga category ng hardware store (gagamitin din sa form dropdown)
    private array $categories = ['Lumber', 'Hardware', 'Tools', 'Electrical', 'Plumbing', 'Paint'];

    // READ: listahan ng products (may search at category filter)
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('name')->paginate(10)->withQueryString();
        $categories = $this->categories;

        return view('products.index', compact('products', 'categories'));
    }

    // CREATE: ipakita ang form
    public function create()
    {
        $categories = $this->categories;
        return view('products.create', compact('categories'));
    }

    // CREATE: i-save ang bagong product
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully!');
    }

    // READ: detalye ng isang product
    public function show(Product $product)
    {
        $transactions = $product->transactions()->latest()->take(10)->get();
        return view('products.show', compact('product', 'transactions'));
    }

    // UPDATE: ipakita ang edit form
    public function edit(Product $product)
    {
        $categories = $this->categories;
        return view('products.edit', compact('product', 'categories'));
    }

    // UPDATE: i-save ang pagbabago
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate($this->rules($product->id), $this->messages());

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    // DELETE
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    // Validation rules (iisa lang para sa store at update)
    private function rules(?int $ignoreId = null): array
    {
        return [
            'name'          => 'required|string|max:255',
            'sku'           => 'required|string|max:50|unique:products,sku' . ($ignoreId ? ",{$ignoreId}" : ''),
            'description'   => 'nullable|string|max:1000',
            'category'      => 'required|string|in:' . implode(',', $this->categories),
            'quantity'      => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit_price'    => 'required|numeric|min:0.01',
            'supplier'      => 'nullable|string|max:255',
        ];
    }

    // Custom error messages
    private function messages(): array
    {
        return [
            'name.required'     => 'Please enter the product name.',
            'sku.required'      => 'SKU is required.',
            'sku.unique'        => 'This SKU already exists. Use a different one.',
            'category.required' => 'Please select a category.',
            'category.in'       => 'Invalid category selected.',
            'quantity.min'      => 'Quantity cannot be negative.',
            'unit_price.min'    => 'Price must be at least 0.01.',
        ];
    }
}