{{-- Ginagamit ang master layout na layouts/inventory.blade.php (navbar, footer, Bootstrap). Ang laman ng page na ito ay ilalagay sa @yield('content') doon. --}}
@extends('layouts.inventory')

{{-- Ipinapadala ang pamagat ng page sa @yield('title') ng layout. Lalabas sa browser tab bilang "Products - Hardware IMS". --}}
@section('title', 'Products')

{{-- Simula ng laman na ilalagay sa butas na @yield('content') ng layout. --}}
@section('content')

{{-- HEADER NG PAGE: pamagat sa kaliwa at Add Product button sa kanan. d-flex + justify-content-between = magkabilang dulo. mb-3 = espasyo sa ibaba. --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    {{-- Pamagat ng page. mb-0 = tinatanggal ang espasyo sa ibaba para pantay sa button. --}}
    <h2 class="mb-0">Products</h2>
    {{-- Link papunta sa form ng pagdagdag ng product (GET /products/create). Ang btn-theme ay ang purple na button mula sa custom.css. --}}
    <a href="{{ route('products.create') }}" class="btn btn-theme">+ Add Product</a>
</div>

{{-- SEARCH AT FILTER FORM. method="GET" = ang laman ng form ay ilalagay sa URL (hal. ?search=PVC), kaya madaling i-bookmark at gumagana ang pagination. --}}
<form method="GET" action="{{ route('products.index') }}" class="row g-2 mb-3">
    {{-- Kolum ng search box: kalahati ng lapad sa medium na screen pataas (col-md-6). --}}
    <div class="col-md-6">
        {{-- Textbox para sa paghahanap. Ang value ay ang dating tinype (request('search')) para hindi mawala pagkatapos mag-filter. --}}
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or SKU...">
    </div>
    {{-- Kolum ng category dropdown (col-md-3 = isang-kapat ng lapad). --}}
    <div class="col-md-3">
        {{-- Dropdown ng category. Ang name="category" ang babasahin ng controller sa $request->category. --}}
        <select name="category" class="form-select">
            {{-- Default na opsyon: walang value, ibig sabihin lahat ng category. --}}
            <option value="">All Categories</option>
            {{-- Paulit-ulit para sa bawat category na ipinasa ng ProductController. --}}
            @foreach ($categories as $cat)
                {{-- @selected = ilalagay ang "selected" kung ito ang kasalukuyang piniling category, para hindi mawala ang napili. --}}
                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    {{-- Kolum ng mga button. d-flex gap-2 = magkatabi na may espasyo. --}}
    <div class="col-md-3 d-flex gap-2">
        {{-- Filter button: isusumite ang form. btn-outline-theme = purple na may balangkas. --}}
        <button class="btn btn-outline-theme w-100" type="submit">Filter</button>
        {{-- Reset link: bumabalik sa /products na walang search at filter. --}}
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

{{-- table-responsive = kapag masikip ang screen (phone), pwedeng i-scroll pahalang ang table imbes na masira ang layout. --}}
<div class="table-responsive">
    {{-- Ang table. table-striped = salitan ang kulay ng rows. table-hover = nagbabago ang kulay kapag dinaanan ng mouse. align-middle = nasa gitna patayo ang laman. shadow-sm = bahagyang anino. bg-white = puting background. --}}
    <table class="table table-striped table-hover align-middle shadow-sm bg-white">
        {{-- Ulo ng table (mga pamagat ng column). Purple ito dahil sa custom.css. --}}
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Category</th>
                {{-- text-end = nakahanay sa kanan, para madaling basahin ang mga numero. --}}
                <th class="text-end">Qty</th>
                <th class="text-end">Price</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        {{-- Katawan ng table kung saan ilalagay ang mga row ng produkto. --}}
        <tbody>
            {{-- @forelse = parang foreach, pero may @empty para sa kapag walang laman ang listahan. --}}
            @forelse ($products as $product)
                {{-- Isang row para sa bawat produkto. Kapag mababa o wala nang stock (ayon sa mga method ng Product model), nilalagyan ng class na "row-alert" (dilaw na background). Kung hindi, walang class. --}}
                <tr class="{{ $product->isLowStock() || $product->isOutOfStock() ? 'row-alert' : '' }}">
                    {{-- Ipinapakita ang SKU ng produkto. Ang {{ }} ay awtomatikong nag-e-escape ng HTML para ligtas laban sa XSS. --}}
                    <td>{{ $product->sku }}</td>
                    {{-- Pangalan ng produkto. --}}
                    <td>{{ $product->name }}</td>
                    {{-- Category ng produkto. --}}
                    <td>{{ $product->category }}</td>
                    {{-- Dami sa stock, nakahanay sa kanan. --}}
                    <td class="text-end">{{ $product->quantity }}</td>
                    {{-- Presyo. Ang number_format(..., 2) ay naglalagay ng kuwit at 2 decimal places (hal. 1,200.00). --}}
                    <td class="text-end">₱{{ number_format($product->unit_price, 2) }}</td>
                    {{-- Cell ng status badge. --}}
                    <td>
                        {{-- Kung wala nang stock: pulang badge. --}}
                        @if ($product->isOutOfStock())
                            <span class="badge bg-danger">Out of Stock</span>
                        {{-- Kung mababa ang stock: dilaw na badge. --}}
                        @elseif ($product->isLowStock())
                            <span class="badge bg-warning text-dark">Low Stock</span>
                        {{-- Kung hindi, sapat ang stock: berdeng badge. --}}
                        @else
                            <span class="badge bg-success">In Stock</span>
                        @endif
                    </td>
                    {{-- Cell ng mga action button. text-nowrap = hindi hahatiin sa dalawang linya ang mga button. --}}
                    <td class="text-end text-nowrap">
                        {{-- View: pupunta sa detalye ng produkto (GET /products/{id}). --}}
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        {{-- Edit: pupunta sa edit form (GET /products/{id}/edit). --}}
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-theme">Edit</a>
                        {{-- Delete form. Kailangan ng form dahil POST/DELETE ang pagbura, hindi pwedeng link. onsubmit = magtatanong muna ng confirm; kapag Cancel, hindi isusumite. --}}
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete {{ $product->name }}?');">
                            {{-- CSRF token para tanggapin ng Laravel ang form. --}}
                            @csrf
                            {{-- Ang browser ay POST lang ang kayang ipadala sa form, kaya ginagamit ito para gawing DELETE ang request. --}}
                            @method('DELETE')
                            {{-- Delete button: pula na may balangkas. --}}
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            {{-- Kapag walang produkto sa listahan (halimbawa walang tugma sa search)... --}}
            @empty
                {{-- ...isang row na sumasakop sa lahat ng 7 column na may mensaheng "No products found." --}}
                <tr><td colspan="7" class="text-center text-muted py-4">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- PAGINATION: ipinapakita ang mga link ng page (1, 2, ...) dahil 10 produkto lang kada page (paginate(10) sa controller). Gumagamit ng Bootstrap 5 na disenyo. --}}
{{ $products->links('pagination::bootstrap-5') }}
{{-- Dulo ng section na "content". --}}
@endsection