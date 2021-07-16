@extends('layout.site')

@section('content')
    <h1>{{ $category->name }}</h1>
    <p>{{ $category->content }}</p>
    <div class="row">
        @foreach ($category->products as $product)
            @include('part.product')
        @endforeach
    </div>
@endsection
