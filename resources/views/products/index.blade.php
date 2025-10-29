@extends('layouts.app')

@section('title', 'Catalogue - GearBoost')

@section('content')
<div id="app">
    <product-catalogue 
        :initial-products='@json($products)'
        :initial-categories='@json($categories)'
        :is-authenticated="{{ Auth::check() ? 'true' : 'false' }}"
    ></product-catalogue>
</div>
@endsection
