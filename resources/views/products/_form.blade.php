{{-- Ito ang SHARED FORM: iisang form na ginagamit ng create at edit page (sa pamamagitan ng include), kaya hindi na kailangang ulitin ang code. --}}
{{-- Naglalagay ng nakatagong CSRF token. Kung wala ito, tatanggihan ni Laravel ang form (error 419 Page Expired). --}}
@csrf

{{-- row g-3 = Bootstrap grid na may espasyo sa pagitan ng mga kolum. --}}
<div class="row g-3">
    {{-- PRODUCT NAME: 8 sa 12 kolum (col-md-8) sa medium na screen pataas. --}}
    <div class="col-md-8">
        <label class="form-label">Product Name</label>
        {{-- old('name', ...) = ibabalik ang tinype ng user kapag may validation error. Kung wala, gagamitin ang kasalukuyang value ng product (edit). Ang "?? ''" ay para hindi mag-error sa create page kung saan walang $product. --}}
        {{-- Ang @error('name') ay naglalagay ng class na "is-invalid" (pulang balangkas) kapag may error sa field na ito. --}}
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror">
        {{-- Ipinapakita ang mensahe ng error sa ilalim ng field. Ang $message ay ang custom message mula sa ProductController. --}}
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- SKU: 4 sa 12 kolum. Dapat unique (nasa validation rules ng controller). --}}
    <div class="col-md-4">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
               class="form-control @error('sku') is-invalid @enderror">
        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- CATEGORY: dropdown. --}}
    <div class="col-md-4">
        <label class="form-label">Category</label>
        <select name="category" class="form-select @error('category') is-invalid @enderror">
            {{-- Default na opsyon na walang value, kaya magre-reklamo ang validation kung hindi pumili. --}}
            <option value="">-- Select --</option>
            {{-- Paulit-ulit para sa bawat category na ipinasa ng controller. --}}
            @foreach ($categories as $cat)
                {{-- selected = pipiliin ang category na tinype dati (old) o ang kasalukuyan ng product (edit). --}}
                <option value="{{ $cat }}" @selected(old('category', $product->category ?? '') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- SUPPLIER: opsyonal na field. --}}
    <div class="col-md-4">
        <label class="form-label">Supplier</label>
        <input type="text" name="supplier" value="{{ old('supplier', $product->supplier ?? '') }}"
               class="form-control @error('supplier') is-invalid @enderror">
        @error('supplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- UNIT PRICE: type="number" at step="0.01" = tumatanggap ng sentimo (2 decimal places). --}}
    <div class="col-md-4">
        <label class="form-label">Unit Price (₱)</label>
        <input type="number" step="0.01" name="unit_price" value="{{ old('unit_price', $product->unit_price ?? '') }}"
               class="form-control @error('unit_price') is-invalid @enderror">
        @error('unit_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- QUANTITY: dami sa stock. Ang default sa create ay 0 (ang "?? 0"). --}}
    <div class="col-md-6">
        <label class="form-label">Quantity</label>
        <input type="number" name="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}"
               class="form-control @error('quantity') is-invalid @enderror">
        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- REORDER LEVEL: kapag ang quantity ay umabot dito o bumaba, Low Stock na. Ang default sa create ay 10. --}}
    <div class="col-md-6">
        <label class="form-label">Reorder Level</label>
        <input type="number" name="reorder_level" value="{{ old('reorder_level', $product->reorder_level ?? 10) }}"
               class="form-control @error('reorder_level') is-invalid @enderror">
        @error('reorder_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- DESCRIPTION: buong lapad (col-12), multi-line na textbox na 3 linya ang taas. --}}
    <div class="col-12">
        <label class="form-label">Description</label>
        {{-- Sa textarea, ang value ay nasa pagitan ng tag (hindi sa value attribute), kaya doon nilagay ang old(). --}}
        <textarea name="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{-- MGA BUTTON: mt-4 = espasyo sa itaas, d-flex gap-2 = magkatabi na may espasyo. --}}
<div class="mt-4 d-flex gap-2">
    {{-- Save: isusumite ang form. Purple dahil sa btn-theme. --}}
    <button type="submit" class="btn btn-theme">Save</button>
    {{-- Cancel: bumabalik sa listahan nang hindi nagse-save. --}}
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>