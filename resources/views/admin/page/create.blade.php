@extends('layout.admin', ['title' => 'Створення нової сторінки'])

@section('content')
    <h1>Створення нової сторінки</h1>
    <form method="post" action="{{ route('admin.page.store') }}">
        @include('admin.page.part.form')
    </form>
@endsection
