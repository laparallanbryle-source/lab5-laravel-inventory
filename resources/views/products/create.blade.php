{{-- Ginagamit ang master layout (navbar, footer, Bootstrap). --}}
@extends('layouts.inventory')

{{-- Pamagat sa browser tab: "Add Product - Hardware IMS". --}}
@section('title', 'Add Product')

{{-- Simula ng laman na ilalagay sa content area ng layout. --}}
@section('content')
{{-- Pamagat ng page. mb-3 = espasyo sa ibaba. --}}
<h2 class="mb-3">Add Product</h2>
{{-- Card = puting kahon na may anino para sa form. --}}
<div class="card shadow-sm">
    <div class="card-body">
        {{-- POST papunta sa route na products.store, ang tatawag sa store() method ng ProductController para mag-save. --}}
        <form method="POST" action="{{ route('products.store') }}">
            {{-- Isinasama ang shared form (products/_form.blade.php). Walang $product dito, kaya blangko ang mga field. --}}
            @include('products._form')
        </form>
    </div>
</div>
{{-- Dulo ng content section. --}}
@endsection