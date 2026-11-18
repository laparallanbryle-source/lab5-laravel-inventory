{{-- Ginagamit ang master layout. --}}
@extends('layouts.inventory')

{{-- Pamagat sa browser tab: "Edit Product - Hardware IMS". --}}
@section('title', 'Edit Product')

{{-- Simula ng laman na ilalagay sa content area. --}}
@section('content')
<h2 class="mb-3">Edit Product</h2>
<div class="card shadow-sm">
    <div class="card-body">
        {{-- POST papunta sa route na products.update kasama ang product na ine-edit. Ang tatawaging method ay update() sa ProductController. --}}
        <form method="POST" action="{{ route('products.update', $product) }}">
            {{-- Ang browser form ay POST/GET lang ang kaya, kaya ginagamit ito para gawing PUT ang request, na kailangan ng update route. --}}
            @method('PUT')
            {{-- Isinasama ang shared form. Dito may $product, kaya may laman na ang mga field ng kasalukuyang data. --}}
            @include('products._form')
        </form>
    </div>
</div>
{{-- Dulo ng content section. --}}
@endsection