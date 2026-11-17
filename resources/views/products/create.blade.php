@extends('layouts.inventory')

@section('title', 'Add Product')

@section('content')
<h2 class="mb-3">Add Product</h2>
<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}">
            @include('products._form')
        </form>
    </div>
</div>
@endsection