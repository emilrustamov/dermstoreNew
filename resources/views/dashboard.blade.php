@extends('layouts.app')

@section('content')
    <div class="menu">
        <div class="container" style="display: flex; gap:20px">
            <div class="menu-item">
                BRANDS
                <div class="dropdown">
                    <!-- Буквы алфавита -->
                    <div class="alphabet">
                        @foreach (range('A', 'Z') as $letter)
                            <div class="alphabet-item">
                                <span class="alphabet-letter">{{ $letter }}</span>
                                @php
                                    $brands = \App\Models\Brand::where('name', 'like', "$letter%")->get();
                                @endphp
                                @if ($brands->count())
                                    {{-- <div class="brand-list"> --}}
                                    <ul>
                                        @foreach ($brands as $brand)
                                            <li><a
                                                    href="{{ route('dashboard.byBrand', $brand->id) }}">{{ $brand->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    {{-- </div> --}}
                                @endif
                            </div>
                        @endforeach
                        <!-- Для цифр 0-9 -->
                        <div class="alphabet-item">
                            <span class="alphabet-letter">0-9</span>
                            @php
                                $brands = \App\Models\Brand::whereRaw('name REGEXP "^[0-9]"')->get();
                            @endphp
                            @if ($brands->count())
                                {{-- <div class="brand-list"> --}}
                                <ul>
                                    @foreach ($brands as $brand)
                                        <li><a href="{{ route('dashboard.byBrand', $brand->id) }}">{{ $brand->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                {{-- </div> --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @php
                // Получаем список всех секций
                $sections = \App\Models\Section::all();
            @endphp
            @foreach ($sections as $section)
                <div class="menu-item">
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
            <h1 class="mb-4">Товары</h1>
            @if ($products->count())
                <div class="product-grid">
                    @foreach ($products as $product)
                        <div class="product-card">
                            <div class="product-info">
                                <h3><a href="{{ route('dashboard.product.details', $product->id) }}">{{ $product->name }}</a></h3>
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No products found for the selected filter.</p>
            @endif
        </div>

    </div>

    <style>
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
        }

        .dropdown-column ul li a:hover {
            color: #000;
        }

        .alphabet {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .alphabet-item {
            position: relative;
        }

        .alphabet-letter {
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            color: #333;
        }

        .alphabet-letter:hover {
            color: #000;
        }

        .brand-list {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #fff;
            color: #000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
            padding: 10px;
            white-space: nowrap;
        }

        .alphabet-item:hover .brand-list {
            display: block;
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
@endsection
