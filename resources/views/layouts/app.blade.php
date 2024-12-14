<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        {{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> --}}

        <main class="my-3">
            @yield('content') <!-- Для Blade -->
            {{ $slot ?? '' }}

            @auth
                <div class="footer-menu">
                    <ul>
                        <li><a href="{{ route('dashboard') }}">Главная</a></li>
                        <li><a href="{{ route('sections') }}">Разделы</a></li>
                        <li><a href="{{ route('brands') }}">Бренды</a></li>
                        <li><a href="{{ route('categories') }}">Категории</a></li>
                        <li><a href="{{ route('subcategories') }}">Подкатегории</a></li>
                        <li><a href="{{ route('filters') }}">Фильтры</a></li>
                        <li><a href="{{ route('products') }}">Товары</a></li>
                        <li><a href="{{ route('export.products') }}" class="btn btn-success">Excel</a></li>
                        <li><a href="{{ route('users') }}">Пользователи</a></li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                               Выйти
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
            <!-- Для Livewire -->
        </main>
    </div>

    <style>
        .footer-menu {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        }

        .footer-menu ul {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 20px;
            align-items: center;
        }

        .footer-menu ul li {
            display: inline;
        }

        .footer-menu ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-menu ul li a:hover {
            text-decoration: underline;
        }
    </style>
</body>

</html>
