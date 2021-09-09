@extends('layout.admin')

@section('content')
    <h1>Панель управления</h1>
    <p>Добро пожаловать, {{ auth()->user()->name }}</p>
    <p>Панель управления администратора интернет-магазина.</p>
    <form action="{{ route('user.logout') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-primary">Выйти</button>
    </form>
@endsection
