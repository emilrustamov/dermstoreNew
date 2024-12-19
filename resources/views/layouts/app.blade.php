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
    <!-- Bootstrap CSS -->
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
    <!-- Custom CSS -->
    <style>
        .custom-collapse {
            display: none;
        }

        .custom-collapse.show {
            display: block;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss','resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div id="app">

        <main class="my">

            @auth
                <div class="menu">
                    <div class="container" style="display: flex;">
                        <div class="menu-item">
                            БРЕНДЫ
                            <div class="dropdown">
                                <div class="container dropdown-container" style="padding: 0 40px;">
                                    <!-- Буквы алфавита -->
                                    <div class="alphabet">
                                        <div class="alphabet-letters">
                                            @foreach (range('A', 'Z') as $letter)
                                                <span class="alphabet-letter"
                                                    data-letter="{{ $letter }}">{{ $letter }}</span>
                                            @endforeach
                                            <span class="alphabet-letter" data-letter="0-9">0-9</span>
                                        </div>

                                        <!-- Блок для отображения брендов -->
                                        <div class="brands-display" id="brands-display">
                                            @php
                                                $initialBrands = \App\Models\Brand::where('name', 'like', 'A%')->get();
                                            @endphp

                                            @if ($initialBrands->count())
                                                @foreach ($initialBrands as $brand)
                                                    <a
                                                        href="{{ route('dashboard.byBrand', $brand->id) }}">{{ $brand->name }}</a>
                                                @endforeach
                                            @else
                                                <p>Бренды пока не добавлены</p>
                                            @endif
                                        </div>

                                        <!-- Скрытые данные для всех брендов -->
                                        <div id="all-brands" style="display: none;">
                                            @foreach (range('A', 'Z') as $letter)
                                                <div class="brands-group" data-letter="{{ $letter }}">
                                                    @php
                                                        $brands = \App\Models\Brand::where(
                                                            'name',
                                                            'like',
                                                            "$letter%",
                                                        )->get();
                                                    @endphp
                                                    @if ($brands->count())
                                                        @foreach ($brands as $brand)
                                                            <a
                                                                href="{{ route('dashboard.byBrand', $brand->id) }}">{{ $brand->name }}</a>
                                                        @endforeach
                                                    @else
                                                        <p>Бренды пока не добавлены</p>
                                                    @endif
                                                </div>
                                            @endforeach

                                            <div class="brands-group" data-letter="0-9">
                                                @php
                                                    $numericBrands = \App\Models\Brand::whereRaw(
                                                        'name REGEXP "^[0-9]"',
                                                    )->get();
                                                @endphp
                                                @if ($numericBrands->count())
                                                    @foreach ($numericBrands as $brand)
                                                        <a
                                                            href="{{ route('dashboard.byBrand', $brand->id) }}">{{ $brand->name }}</a>
                                                    @endforeach
                                                @else
                                                    <p>Бренды пока не добавлены</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            // Получаем список всех секций
                            $sections = \App\Models\Section::all();
                        @endphp
                        @foreach ($sections as $section)
                            <div class="menu-item uppercase">
                                <a href="{{ route('dashboard.bySection', $section->id) }}">{{ $section->name }}</a>

                                @php
                                    $categories = $section->categories();
                                @endphp
                                @if ($categories->count())
                                    <div class="dropdown">
                                        <div class="container dropdown-container" style="padding: 0 40px;">
                                            @foreach ($categories as $category)
                                                <div class="dropdown-column">
                                                    <h4><a href="{{ route('dashboard.byCategory', $category->id) }}"
                                                            class="category">{{ $category->name }}</a>
                                                        <hr class="mt-1 mb-2">
                                                    </h4>

                                                    @php
                                                        $subcategories = $category->subcategories();
                                                    @endphp
                                                    @if ($subcategories->count())
                                                        <ul>
                                                            @foreach ($subcategories as $subcategory)
                                                                <li>
                                                                    <a href="{{ route('dashboard.bySubcategory', $subcategory->id) }}"
                                                                        class="subcategory">{{ $subcategory->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endauth
            @yield('content') <!-- Для Blade -->
            {{ $slot ?? '' }}
            @auth
                <div class="footer-menu">
                    <ul>
                        <li><a href="{{ route('dashboard') }}">Главная</a></li>
                        <li><a href="{{ route('brands') }}">Бренды</a></li>
                        <li><a href="{{ route('ranges') }}">Линейка бренда</a></li>
                        <li><a href="{{ route('sections') }}">Разделы</a></li>
                        <li><a href="{{ route('categories') }}">Категории 1ур</a></li>
                        <li><a href="{{ route('subcategories') }}">Категории 2ур</a></li>
                        <li><a href="{{ route('subsubcategories') }}">Категории 3ур</a></li>
                        <li><a href="{{ route('characteristics') }}">Характеристики</a></li>
                        <li><a href="{{ route('filters') }}">Фильтры</a></li>
                        <li><a href="{{ route('products') }}">Товары</a></li>
                        <li><a href="{{ route('export.products') }}" class="btn btn-success">Excel</a></li>
                        <li><a href="{{ route('users') }}">Пользователи</a></li>
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

   
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const letters = document.querySelectorAll('.alphabet-letter');
            const brandsDisplay = document.getElementById('brands-display');
            const allBrands = document.getElementById('all-brands');

            letters.forEach(letter => {
                letter.addEventListener('mouseover', () => {
                    const selectedLetter = letter.getAttribute('data-letter');

                    // Найти соответствующую группу брендов
                    const group = allBrands.querySelector(
                        `.brands-group[data-letter="${selectedLetter}"]`);

                    // Очистить текущий список брендов
                    brandsDisplay.innerHTML = '';

                    // Добавить бренды из выбранной группы
                    if (group) {
                        brandsDisplay.innerHTML = group.innerHTML;
                    }
                });
            });
        });
    </script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
