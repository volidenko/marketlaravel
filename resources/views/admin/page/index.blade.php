@extends('layout.admin', ['title' => 'Всі сторінки сайту'])

@section('content')
    <h1 class="mb-3">Всі сторінки сайту</h1>
    <a href="{{ route('admin.page.create') }}" class="btn btn-success mb-4">
        Створити сторінку
    </a>
    @if (count($pages))
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th width="45%">Найменування</th>
                <th width="45%">Найменування (англ.)</th>
                <th><i class="fas fa-edit"></i></th>
                <th><i class="fas fa-trash-alt"></i></th>
            </tr>
            @include('admin.page.part.tree', ['level' => -1, 'parent' => 0])
        </table>
    @endif
@endsection
