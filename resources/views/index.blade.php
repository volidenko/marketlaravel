@extends('layout.site')

@section('content')
    <h1>Интернет-магазин автозапчастей</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel volutpat lectus....</p>
    <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas....</p>
    <p>Aliquam ipsum lectus, tristique sed scelerisque ac, gravida in neque....</p>

    {{-- @if($new->count())
    <h2>Новинки</h2>
    <div class="row">
    @foreach($new as $item)
        @include('catalog.part.product', ['product' => $item])
    @endforeach
    </div>
@endif

@if($hit->count())
    <h2>Лидеры продаж</h2>
    <div class="row">
        @foreach($hit as $item)
            @include('catalog.part.product', ['product' => $item])
        @endforeach
    </div>
@endif

@if($sale->count())
    <h2>Распродажа</h2>
    <div class="row">
        @foreach($sale as $item)
            @include('catalog.part.product', ['product' => $item])
        @endforeach
    </div>
@endif --}}
@endsection
