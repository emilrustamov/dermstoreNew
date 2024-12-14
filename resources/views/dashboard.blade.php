@extends('layouts.app')

@section('content')
    <div class="menu">
        <div class="container" style="display: flex; gap:20px">
            <div class="menu-item">
                БРЕНДЫ
                <div class="dropdown">
                    <!-- Буквы алфавита -->
                    <div class="alphabet">
                        <div class="alphabet-letters">
                            @foreach (range('A', 'Z') as $letter)
                                <span class="alphabet-letter" data-letter="{{ $letter }}">{{ $letter }}</span>
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
                                    <a href="{{ route('dashboard.byBrand', $brand->id) }}">{{ $brand->name }}</a>
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
                                        $brands = \App\Models\Brand::where('name', 'like', "$letter%")->get();
                                    @endphp
                                    @if ($brands->count())
                                        @foreach ($brands as $brand)
                                            <a href="{{ route('dashboard.byBrand', $brand->id) }}">{{ $brand->name }}</a>
                                        @endforeach
                                    @else
                                        <p>Бренды пока не добавлены</p>
                                    @endif
                                </div>
                            @endforeach

                            <div class="brands-group" data-letter="0-9">
                                @php
                                    $numericBrands = \App\Models\Brand::whereRaw('name REGEXP "^[0-9]"')->get();
                                @endphp
                                @if ($numericBrands->count())
                                    @foreach ($numericBrands as $brand)
                                        <a href="{{ route('dashboard.byBrand', $brand->id) }}">{{ $brand->name }}</a>
                                    @endforeach
                                @else
                                    <p>Бренды пока не добавлены</p>
                                @endif
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
                            @foreach ($categories as $category)
                                <div class="dropdown-column">
                                    <h4><a
                                            href="{{ route('dashboard.byCategory', $category->id) }}">{{ $category->name }}</a>
                                        <hr>
                                    </h4>

                                    @php
                                        $subcategories = $category->subcategories();
                                    @endphp
                                    @if ($subcategories->count())
                                        <ul>
                                            @foreach ($subcategories as $subcategory)
                                                <li>
                                                    <a
                                                        href="{{ route('dashboard.bySubcategory', $subcategory->id) }}">{{ $subcategory->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <div class="container">
        @php
            $productsQuery = \App\Models\Product::query();

            if (isset($categoryId)) {
                $productsQuery->whereJsonContains('categories', (string) $categoryId);
            }

            if (isset($subcategoryId)) {
                $productsQuery->whereJsonContains('subcategories', (string) $subcategoryId);
            }

            if (isset($brandId)) {
                $productsQuery->whereJsonContains('brands', (string) $brandId);
            }

            if (isset($sectionId)) {
                $productsQuery->whereJsonContains('sections', (string) $sectionId);
            }

            $products = $productsQuery->get();
        @endphp



        <div class="product-container my-4">

            @php
                use Carbon\Carbon;
                use App\Models\Section;
                use App\Models\Category;
                use App\Models\Subcategory;
                use App\Models\Brand;
                use App\Models\Filter;
                use App\Models\Product;

                $stats = [
                    'sections_total' => Section::count(),
                    'sections_today' => Section::whereDate('created_at', Carbon::today())->count(),
                    'categories_total' => Category::count(),
                    'categories_today' => Category::whereDate('created_at', Carbon::today())->count(),
                    'subcategories_total' => Subcategory::count(),
                    'subcategories_today' => Subcategory::whereDate('created_at', Carbon::today())->count(),
                    'brands_total' => Brand::count(),
                    'brands_today' => Brand::whereDate('created_at', Carbon::today())->count(),
                    'filters_total' => Filter::count(),
                    'filters_today' => Filter::whereDate('created_at', Carbon::today())->count(),
                    'products_total' => Product::count(),
                    'products_today' => Product::whereDate('created_at', Carbon::today())->count(),
                ];
            @endphp
            <style>
                .dashboard-stats {
                    display: flex;
                    flex-direction: column;
                    margin-bottom: 20px;
                }

                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 20px;
                }

                .stat-card {
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 15px;
                    background: #f9f9f9;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
            </style>
            <div class="dashboard-stats my-4">
                <h2 class="text-xl font-bold mb-4">Общая статистика</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Разделы</h3>
                        <p>Всего: {{ $stats['sections_total'] }}</p>
                        <p>Создано сегодня: {{ $stats['sections_today'] }}</p>
                    </div>
                    <div class="stat-card">
                        <h3>Категории</h3>
                        <p>Всего: {{ $stats['categories_total'] }}</p>
                        <p>Создано сегодня: {{ $stats['categories_today'] }}</p>
                    </div>
                    <div class="stat-card">
                        <h3>Подкатегории</h3>
                        <p>Всего: {{ $stats['subcategories_total'] }}</p>
                        <p>Создано сегодня: {{ $stats['subcategories_today'] }}</p>
                    </div>
                    <div class="stat-card">
                        <h3>Бренды</h3>
                        <p>Всего: {{ $stats['brands_total'] }}</p>
                        <p>Создано сегодня: {{ $stats['brands_today'] }}</p>
                    </div>
                    <div class="stat-card">
                        <h3>Фильтры</h3>
                        <p>Всего: {{ $stats['filters_total'] }}</p>
                        <p>Создано сегодня: {{ $stats['filters_today'] }}</p>
                    </div>
                    <div class="stat-card">
                        <h3>Товары</h3>
                        <p>Всего: {{ $stats['products_total'] }}</p>
                        <p>Создано сегодня: {{ $stats['products_today'] }}</p>
                    </div>
                </div>
            </div>

            <h1 class="mb-4 text-xl font-bold mb-4">Товары</h1>



            @if ($products->count())
                <div class="product-grid">
                    @foreach ($products as $product)
                        <div class="product-card">
                            <div class="product-info">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="product-image">
                                @else
                                    <img src="{{ asset('images/default-product.png') }}" alt="Изображение отсутствует"
                                        class="product-image">
                                @endif
                                <h3><a
                                        href="{{ route('dashboard.product.details', $product->id) }}">{{ $product->name }}</a>
                                </h3>
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>Товаров пока не добавлено</p>
            @endif
        </div>

    </div>

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
            padding: 15px;
        }

        .menu-item {
            position: relative;
            cursor: pointer;
            font-weight: bold;
        }

        .menu-item:hover {
            background-color: #444;
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
            color: #444;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .dropdown-column ul li a:hover {
            color: #000;
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
        }

        .alphabet-letter {
            cursor: pointer;
            font-weight: bold;

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
@endsection
