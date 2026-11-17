@csrf

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
               class="form-control @error('sku') is-invalid @enderror">
        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Category</label>
        <select name="category" class="form-select @error('category') is-invalid @enderror">
            <option value="">-- Select --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}" @selected(old('category', $product->category ?? '') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Supplier</label>
        <input type="text" name="supplier" value="{{ old('supplier', $product->supplier ?? '') }}"
               class="form-control @error('supplier') is-invalid @enderror">
        @error('supplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Unit Price (₱)</label>
        <input type="number" step="0.01" name="unit_price" value="{{ old('unit_price', $product->unit_price ?? '') }}"
               class="form-control @error('unit_price') is-invalid @enderror">
        @error('unit_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Quantity</label>
        <input type="number" name="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}"
               class="form-control @error('quantity') is-invalid @enderror">
        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Reorder Level</label>
        <input type="number" name="reorder_level" value="{{ old('reorder_level', $product->reorder_level ?? 10) }}"
               class="form-control @error('reorder_level') is-invalid @enderror">
        @error('reorder_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-theme">Save</button>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>