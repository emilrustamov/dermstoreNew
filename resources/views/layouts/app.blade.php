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
                        <li><a href="{{ route('sections') }}">Разделы</a></li>
                        <li><a href="{{ route('brands') }}">Бренды</a></li>
                        <li><a href="{{ route('ranges') }}">Range бренда</a></li>
                        <li><a href="{{ route('categories') }}">Категории</a></li>
                        <li><a href="{{ route('subcategories') }}">Подкатегории</a></li>
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

    <style>
        .footer-menu {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #000;
            color: #fff;
            text-align: center;
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
    <style>
        .product-image {
            width: 150px;
            /* Ширина миниатюры */
            height: 150px;
            /* Высота миниатюры */
            object-fit: cover;
            /* Обрезка или масштабирование изображения */
            border-radius: 8px;
            /* Радиус углов */
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        /* Основные стили меню */
        .menu {
            display: flex;
            background-color: #000;
            color: #fff;
            position: relative;
        }

        .menu-item {
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: background-color 0.3s, color 0.3s;
            padding: 10px 30px;
        }

        

        .subcategory {
            font-size: 14px;
            text-transform: capitalize;
            font-weight: 400;
            color: #121212;
        }

        .category {
            font-size: 12px;
            font-weight: 600;
            color: #121212;
        }

        .menu-item:hover {
            background-color: #fff;
            color: #000;

        }

        /* Стили для выпадающего списка */
        .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #fff;
            color: #000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
            padding: 20px;
            white-space: nowrap;
            width: 100vw;
        }

        .dropdown-container {
            display: flex;
            gap: 100px;
        }

        .menu-item:hover .dropdown {
            display: flex;
            gap: 30px;
        }

        .dropdown-column {
            margin-right: 20px;
        }

        .dropdown-column h4 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #000;
        }

        .dropdown-column ul {
            list-style: none;
            padding: 0;
        }

        .dropdown-column ul li {
            margin-bottom: 5px;
        }

        .dropdown-column ul li a {
padding: 3px;
            text-decoration: none;
        }
        .dropdown-column ul li a:hover {
            background-color: #e5e7eb;
        }
        .alphabet {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-direction: column;
            align-items: start;
        }

        .alphabet-letter {
            cursor: pointer;
            font-weight: bold;
            padding: 5px 10px;
            display: inline-block;
            text-align: center;
            font-size: 14px;
        }

        .alphabet-letter {
            cursor: pointer;
            font-weight: 400;

            display: inline-flex;
            text-align: center;
        }

        .alphabet-letter:hover {
            color: #000;
        }

        .brands-display {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            padding: 10px;
            font-size: 14px;
            font-weight: 400;
            text-transform: capitalize;
        }

        .brands-display a {
            text-decoration: none;
            color: black;
            border-radius: 4px;
        }

        .brands-display a:hover {
            text-decoration: underline;
        }

        #all-brands {
            display: none;
        }

        .brand-list {
            display: none;
            /* Скрываем бренды по умолчанию */
            position: absolute;
            top: 100%;
            /* Располагаем бренды под буквой */
            left: 0;
            white-space: nowrap;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 10;
            padding: 10px;
        }

        .alphabet-item:hover .brand-list {
            display: block;
        }

        .alphabet-letters {
            display: flex;
            gap: 20px;
            border-bottom: 1px solid #ccc;
        }

        .brand-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .brand-list ul li {
            margin-bottom: 5px;
        }

        .brand-list ul li a {
            color: #444;
            text-decoration: none;
            font-size: 14px;
        }

        .brand-list ul li a:hover {
            color: #000;
        }



        /* Сетка для карточек */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        /* Карточка товара */
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            background: #fff;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }



        /* Информация о продукте */
        .product-info {
            padding: 15px;
            text-align: center;
        }

        .product-info h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .product-info p {
            font-size: 14px;
            color: #666;
        }
    </style>
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
</body>

</html>
