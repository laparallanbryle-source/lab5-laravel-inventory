<?php

// Nasa folder app/Http/Controllers ang class na ito.
namespace App\Http\Controllers;

// Ang Product model, para makausap ang "products" table.
use App\Models\Product;
// Ang Request class: dito nakalagay ang lahat ng galing sa form o URL (search, category, input).
use Illuminate\Http\Request;
// Ang DB facade, ginagamit natin para sa DB::transaction() sa stock in/out.
use Illuminate\Support\Facades\DB;

// Ang CONTROLLER sa MVC: tumatanggap ng request, kumukuha ng data sa Model, at pumipili ng View.
class ProductController extends Controller
{
    // Listahan ng mga category ng hardware store. Ginagamit sa dropdown at sa validation (in:...).
    private array $categories = ['Lumber', 'Hardware', 'Tools', 'Electrical', 'Plumbing', 'Paint'];

    // READ (listahan): tinatawag kapag binuksan ang /products
    public function index(Request $request)
    {
        // Nagsisimula ng query sa products table (wala pang filter at hindi pa kinukuha ang data).
        $query = Product::query();

        // Kung may tinype sa search box (hindi blangko)...
        if ($request->filled('search')) {
            // Kinukuha ang tinype ng user.
            $search = $request->search;
            // Naghahanap sa name O sa sku. Ang function na naka-group ay para tama ang AND/OR sa SQL.
            $query->where(function ($q) use ($search) {
                // Tugma ang name sa kahit anong bahagi ng salita (%...%).
                $q->where('name', 'like', "%{$search}%")
                  // O tugma ang sku.
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Kung may pinili sa category dropdown...
        if ($request->filled('category')) {
            // Ipakita lang ang mga produkto sa category na iyon.
            $query->where('category', $request->category);
        }

        // Ayusin ayon sa pangalan, 10 kada page, at isama ang search/category sa mga link ng pagination.
        $products = $query->orderBy('name')->paginate(10)->withQueryString();
        // Ipapasa ang categories para sa dropdown sa view.
        $categories = $this->categories;

        // Ipakita ang view na products/index.blade.php kasama ang $products at $categories.
        return view('products.index', compact('products', 'categories'));
    }

    // CREATE (form): tinatawag kapag binuksan ang /products/create
    public function create()
    {
        // Categories para sa dropdown ng form.
        $categories = $this->categories;
        // Ipakita ang form na products/create.blade.php.
        return view('products.create', compact('categories'));
    }

    // CREATE (save): tinatawag kapag sinubmit ang Add Product form
    public function store(Request $request)
    {
        // I-validate ang input gamit ang rules() at messages(). Kapag may mali, awtomatikong babalik sa form na may errors.
        $validated = $request->validate($this->rules(), $this->messages());

        // I-save ang bagong product sa database (mass assignment, kaya kailangan ang $fillable sa Model).
        Product::create($validated);

        // Ibalik sa listahan na may green na success message (flash message).
        return redirect()->route('products.index')
            ->with('success', 'Product added successfully!');
    }

    // READ (isa): tinatawag kapag binuksan ang /products/{id}
    // Ang "Product $product" ay awtomatikong hinahanap ni Laravel ang product ayon sa id sa URL (route model binding).
    public function show(Product $product)
    {
        // Kinukuha ang 10 pinakabagong transactions ng product, kasama ang user (with) para hindi paulit-ulit ang query.
        $transactions = $product->transactions()->with('user')->latest()->take(10)->get();
        // Ipakita ang products/show.blade.php.
        return view('products.show', compact('product', 'transactions'));
    }

    // UPDATE (form): tinatawag kapag binuksan ang /products/{id}/edit
    public function edit(Product $product)
    {
        // Categories para sa dropdown.
        $categories = $this->categories;
        // Ipakita ang edit form na may laman na ang kasalukuyang data ng product.
        return view('products.edit', compact('product', 'categories'));
    }

    // UPDATE (save): tinatawag kapag sinubmit ang Edit form
    public function update(Request $request, Product $product)
    {
        // I-validate. Ipinapasa ang id ng product para hindi ituring na duplicate ang sarili niyang SKU.
        $validated = $request->validate($this->rules($product->id), $this->messages());

        // I-update ang product sa database gamit ang validated na data.
        $product->update($validated);

        // Balik sa listahan na may success message.
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    // DELETE: tinatawag kapag pinindot ang Delete button (at nag-confirm)
    public function destroy(Product $product)
    {
        // Burahin ang product sa database.
        $product->delete();

        // Balik sa listahan na may success message.
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    // STOCK IN / OUT: nag-a-update ng quantity at nagtatala ng transaction
    public function adjustStock(Request $request, Product $product)
    {
        // I-validate ang form: type ay 'in' o 'out' lang, quantity ay buong numero na hindi bababa sa 1, reference ay opsyonal.
        $data = $request->validate([
            'type'      => 'required|in:in,out',
            'quantity'  => 'required|integer|min:1',
            'reference' => 'nullable|string|max:255',
        ]);

        // Kapag stock out at mas marami ang kukunin kaysa sa laman ng stock...
        if ($data['type'] === 'out' && $data['quantity'] > $product->quantity) {
            // Ibalik sa form na may error message at ang mga tinype na (withInput).
            return back()
                ->withErrors(['quantity' => 'Stock out exceeds current stock (' . $product->quantity . ').'])
                ->withInput();
        }

        // DB::transaction = lahat o wala: kung may pumalya sa loob, hindi masasave ang kahit alin (para consistent ang data).
        DB::transaction(function () use ($data, $product, $request) {
            // Kapag 'in', dagdagan ang quantity. Kapag 'out', bawasan.
            $product->quantity += $data['type'] === 'in' ? $data['quantity'] : -$data['quantity'];
            // I-save ang bagong quantity sa products table.
            $product->save();

            // Magtala ng bagong row sa inventory_transactions (kasaysayan ng stock).
            $product->transactions()->create([
                'type'      => $data['type'],               // in o out
                'quantity'  => $data['quantity'],           // ilan ang pumasok/lumabas
                'reference' => $data['reference'] ?? null,  // hal. PO-1001; null kung wala
                'user_id'   => $request->user()->id,        // sino ang naka-login na gumawa nito
            ]);
        });

        // Balik sa show page ng product na may success message.
        return redirect()->route('products.show', $product)
            ->with('success', 'Stock updated successfully!');
    }

    // VALIDATION RULES: iisa lang para sa store at update. May $ignoreId para sa update.
    private function rules(?int $ignoreId = null): array
    {
        return [
            // Pangalan: kailangan, text, hanggang 255 na letra.
            'name'          => 'required|string|max:255',
            // SKU: kailangan, hanggang 50 letra, at unique sa products table (maliban sa sarili kapag update).
            'sku'           => 'required|string|max:50|unique:products,sku' . ($ignoreId ? ",{$ignoreId}" : ''),
            // Description: opsyonal, hanggang 1000 letra.
            'description'   => 'nullable|string|max:1000',
            // Category: kailangan at dapat nasa listahan ng $categories.
            'category'      => 'required|string|in:' . implode(',', $this->categories),
            // Quantity: kailangan, buong numero, hindi negatibo.
            'quantity'      => 'required|integer|min:0',
            // Reorder level: kailangan, buong numero, hindi negatibo.
            'reorder_level' => 'required|integer|min:0',
            // Presyo: kailangan, numero, hindi bababa sa 0.01.
            'unit_price'    => 'required|numeric|min:0.01',
            // Supplier: opsyonal, hanggang 255 na letra.
            'supplier'      => 'nullable|string|max:255',
        ];
    }

    // CUSTOM ERROR MESSAGES: mas malinaw na mensahe kaysa sa default ni Laravel.
    private function messages(): array
    {
        return [
            'name.required'     => 'Please enter the product name.',               // walang name
            'sku.required'      => 'SKU is required.',                             // walang SKU
            'sku.unique'        => 'This SKU already exists. Use a different one.', // duplicate SKU
            'category.required' => 'Please select a category.',                    // walang napiling category
            'category.in'       => 'Invalid category selected.',                   // category na wala sa listahan
            'quantity.min'      => 'Quantity cannot be negative.',                 // negatibong quantity
            'unit_price.min'    => 'Price must be at least 0.01.',                 // presyong masyadong mababa
        ];
    }
}