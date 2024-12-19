@extends('layouts.app')

@section('content')

    <div class="container" style="padding: 0 40px;">
        @php
            $productsQuery = \App\Models\Product::query();

            if (request('categoryId')) {
                $productsQuery->where('categories', 'LIKE', '%"' . (string) request('categoryId') . '"%');
            }

            if (request('subcategoryId')) {
                $productsQuery->where('subcategories', 'LIKE', '%"' . (string) request('subcategoryId') . '"%');
            }

            if (request('brandId')) {
                $productsQuery->where('brands', 'LIKE', '%"' . (string) request('brandId') . '"%');
            }

            if (request('sectionId')) {
                $productsQuery->where('sections', 'LIKE', '%"' . (string) request('sectionId') . '"%');
            }

            $products = $productsQuery->get();
        @endphp
        <!-- Brand Filter -->
        <div class="brand-filter my-4">
            <form action="{{ url()->current() }}" method="GET" style="display: flex; align-items: center;">
            <div class="form-group" style="margin-right: 10px;">
                {{-- <label for="brand" style="margin-right: 10px;">Фильтр по брендам</label> --}}
                <select name="brandId" id="brand" class="form-control">
                <option value="">Выберите бренд</option>
                @foreach (\App\Models\Brand::all() as $brand)
                    <option value="{{ $brand->id }}" {{ request('brandId') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                    </option>
                @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Фильтровать</button>
            </form>
        </div>

        @if (isset($sectionId) || isset($categoryId) || isset($subcategoryId))
            @php
                $entity = null;
                $popularLinks = [];

                if (isset($sectionId)) {
                    $entity = \App\Models\Section::find($sectionId);
                } elseif (isset($categoryId)) {
                    $entity = \App\Models\Category::find($categoryId);
                } elseif (isset($subcategoryId)) {
                    $entity = \App\Models\Subcategory::find($subcategoryId);
                }

                if ($entity) {
                    $popularLinks = explode(',', $entity->selected_links);
                }
            @endphp

            @if (!empty($popularLinks))
                <div class="popular-links my-4">
                    <h2 class="text-xl font-bold mb-4">Популярные ссылки</h2>
                    <div class="popular-links-grid">
                        @foreach ($popularLinks as $linkId)
                            @php
                                $link = null;
                                if (strpos($linkId, 'category_') === 0) {
                                    $link = \App\Models\Category::find(str_replace('category_', '', $linkId));
                                } elseif (strpos($linkId, 'subcategory_') === 0) {
                                    $link = \App\Models\Subcategory::find(str_replace('subcategory_', '', $linkId));
                                } elseif (strpos($linkId, 'subsubcategory_') === 0) {
                                    $link = \App\Models\Subsubcategory::find(
                                        str_replace('subsubcategory_', '', $linkId),
                                    );
                                }
                            @endphp
                            @if ($link)
                                <div class="popular-link-card">
                                    <a
                                        href="{{ route('dashboard.by' . class_basename($link), $link->id) }}">{{ $link->name }}</a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

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
            @if (request()->is('dashboard'))
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
            @endif

            <h1 class="mb-4 text-xl font-bold mb-4">
                @if (request()->is('dashboard'))
                    Последние добавленные товары
                @else
                    Товары
                    @if (isset($categoryId) && ($category = \App\Models\Category::find($categoryId)))
                        из категории {{ $category->name }}
                    @elseif (isset($subcategoryId) && ($subcategory = \App\Models\Subcategory::find($subcategoryId)))
                        из подкатегории {{ $subcategory->name }}
                    @elseif (isset($brandId) && ($brand = \App\Models\Brand::find($brandId)))
                        бренда {{ $brand->name }}
                    @elseif (isset($sectionId) && ($section = \App\Models\Section::find($sectionId)))
                        из раздела {{ $section->name }}
                    @endif
                @endif
            </h1>

            @if ($products->count())
                <div class="product-grid">
                    @foreach ($products as $product)
                        <a href="{{ route('dashboard.product.details', $product->id) }}" class="product-card">
                            <div class="product-info">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="product-image">
                                @else
                                    <img src="https://via.placeholder.com/150?text=No+Image" alt="Изображение отсутствует"
                                        class="product-image">
                                @endif
                                <h3>{{ $product->name }}</h3>
                                {{-- <p>{{ $product->description }}</p> --}}
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p>Товаров пока не добавлено</p>
            @endif
        </div>

    </div>


@endsection
