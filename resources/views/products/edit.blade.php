@extends('layouts.inventory')

@section('title', 'Edit Product')

@section('content')
<h2 class="mb-3">Edit Product</h2>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @method('PUT')
            @include('products._form')
        </form>
    </div>
</div>
@endsection