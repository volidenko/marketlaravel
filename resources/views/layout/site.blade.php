<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Интернет-магазин' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/site.js') }}"></script>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <!-- Бренд -->
            <a class="navbar-brand" href="{{ route('index') }}">Главная</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-example"
                aria-controls="navbar-example" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Основное меню -->
            <div class="collapse navbar-collapse" id="navbar-example">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('catalog.index') }}">Каталог</a>
                    </li>
                    @include('layout.part.pages')
                </ul>
                <form action="{{ route('catalog.search') }}" class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" name="query" placeholder="Поиск по каталогу"
                        aria-label="Search">
                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Поиск</button>
                </form>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item" id="top-basket">
                        <a class="nav-link @if ($positions) text-success @endif" href="{{ route('basket.index') }}">
                            Корзина
                            @if ($positions) ({{ $positions }}) @endif
                        </a>
                    </li>
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.login') }}">Войти</a>
                    </li>
                    @if (Route::has('user.register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.register') }}">Регистрация</a>
                        </li>
                    @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.index') }}">Личный кабинет</a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        <div class="row">
            <div class="col-md-2">
                @include('layout.part.roots')
                @include('layout.part.brands')
            </div>
            <div class="col-md-10">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible mt-0" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ $message }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible mt-4" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
        <footer class="row bottom">
            <div class="col-12 mx-4">
                <div class="div1">
                    <div class="wrap">
                        <div class="block1">
                            <p><a href="{{ route('index') }}">Home</a></p>
                            <p><a href="#">About</a></p>
                            <p><a href="#">Gallery</a></p>
                            <p><a href="#">News</a></p>
                        </div>
                        <div class="block1">
                            <p><a href="#">Blog</a></p>
                            <p><a href="#">FAQ</a></p>
                            <p><a href="#">Contacts</a></p>
                            <p><a href="#">Privacy & cookies</a></p>
                        </div>
                        <div class="block2">
                            <figure>
                                <a id="p1" href="#" class="facebook"></a>
                                <a id="p1" href="#" class="instagram"></a>
                                <a id="p1" href="#" class="youtube"></a>
                            </figure>
                        </div>
                    </div>
                    <hr>
                </div>
                <h5 class="copyright">VHordiienko &copy; 2021</h5>
            </div>
        </footer>
    </div>
</body>
</html>
