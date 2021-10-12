@extends('layout.admin', ['title' => 'Перегляд сторінки'])

@section('content')
    <h1>Перегляд сторінки</h1>
    <div class="row">
        <div class="col-12">
            <p><strong>Назва:</strong> {{ $page->name }}</p>
            <p><strong>Назва (англ.):</strong> {{ $page->slug }}</p>
            <p><strong>Контент (html)</strong></p>
            <div class="mb-4 bg-white p-1">
                @php echo nl2br(htmlspecialchars($page->content)) @endphp
            </div>
            <a href="{{ route('admin.page.edit', ['page' => $page->id]) }}"
               class="btn btn-success">
                Редагувати сторінку
            </a>
            <form method="post" class="d-inline"  onsubmit="return confirm('Видалити цю сторінку?')"
                  action="{{ route('admin.page.destroy', ['page' => $page->id]) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    Удалити сторінку
                </button>
            </form>
        </div>
    </div>
@endsection
