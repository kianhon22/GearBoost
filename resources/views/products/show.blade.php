@extends('layouts.app')

@section('title', $product->name . ' - GearBoost')

@section('content')
<div id="app">
    <a href="{{ route('products.index') }}" class="inline-block mb-8 text-purple-600 hover:text-purple-800 font-semibold transition-colors duration-200">
        ← Go Back
    </a>

    <product-detail 
        :product='@json($product)'
        :is-authenticated="{{ Auth::check() ? 'true' : 'false' }}"
    ></product-detail>
</div>
@endsection
