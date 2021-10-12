@extends('layout.admin', ['title' => 'Редагування сторінки'])

@section('content')
<h1>Редагування сторінки</h1>
<form method="post" action="{{ route('admin.page.update', ['page' => $page->id]) }}">
    @method('PUT')
    @include('admin.page.part.form')
</form>
@endsection
