@extends('layouts.app')

@section('content')
    <div class="container my-4" style="padding: 0 40px;">
        <h1 text-xl font-bold mb-4>Информация о товаре</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Просмотр информации о товаре -->
        @if (!request()->has('edit'))
            <div>
                <table class="table table-bordered">
                    <tr>
                        <th>Название</th>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th>Описание</th>
                        <td>{{ $product->description }}</td>
                    </tr>
                    <tr>
                        <th>Изображение</th>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Изображение товара" style="max-width: 200px; height: auto;">
                            @else
                                Изображение отсутствует
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Разделы</th>
                        <td>{{ implode(', ',\App\Models\Section::whereIn('id', $product->sections ?? [])->pluck('name')->toArray()) }}</td>
                    </tr>
                    <tr>
                        <th>Категории</th>
                        <td>{{ implode(', ',\App\Models\Category::whereIn('id', $product->categories ?? [])->pluck('name')->toArray()) }}</td>
                    </tr>
                    <tr>
                        <th>Подкатегории</th>
                        <td>{{ implode(', ',\App\Models\Subcategory::whereIn('id', $product->subcategories ?? [])->pluck('name')->toArray()) }}</td>
                    </tr>
                    <tr>
                        <th>Подподкатегории</th>
                        <td>{{ implode(', ',\App\Models\Subsubcategory::whereIn('id', $product->subsubcategories ?? [])->pluck('name')->toArray()) }}</td>
                    </tr>
                    <tr>
                        <th>Бренды</th>
                        <td>{{ implode(', ',\App\Models\Brand::whereIn('id', $product->brands ?? [])->pluck('name')->toArray()) }}</td>
                    </tr>
                    <tr>
                        <th>Линейка бренда</th>
                        <td>
                            @if (!empty($product->ranges) && is_array($product->ranges))
                                {{ implode(', ', $product->ranges) }}
                            @else
                                Нет данных
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Фильтры</th>
                        <td>
                            @foreach ($product->filters ?? [] as $filterId => $values)
                                <p><strong>{{ \App\Models\Filter::where('id', $filterId)->value('name') }}:</strong> {{ implode(', ', $values) }}</p>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>Характеристики</th>
                        <td>
                            @foreach ($product->characteristics ?? [] as $characteristicId => $value)
                                <p><strong>{{ \App\Models\Characteristic::where('id', $characteristicId)->value('name') }}:</strong> {{ $value }}</p>
                            @endforeach
                        </td>
                    </tr>
                </table>

                @if (Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ request()->fullUrlWithQuery(['edit' => true]) }}" class="btn btn-primary mt-3">Редактировать</a>
                @endif
            </div>
        @else
            <!-- Форма редактирования -->
            <form action="{{ route('dashboard.product.update', $product->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <!-- Название -->
                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" name="name" id="name" value="{{ $product->name }}" class="form-control"
                        required>
                </div>

                <!-- Описание -->
                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea name="description" id="description" class="form-control" required>{{ $product->description }}</textarea>
                </div>

                <!-- Изображение -->
                <div class="form-group">
                    <label for="image">Изображение</label>
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Изображение товара"
                            style="max-width: 200px; height: auto;" class="mb-2">
                    @endif
                    <input type="file" name="image" id="image" class="form-control">
                </div>

                <!-- Разделы -->
                <div class="form-group">
                    <label>Разделы</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (\App\Models\Section::all() as $section)
                            <div class="form-check-inline">
                                <input type="checkbox" name="sections[]" value="{{ $section->id }}"
                                    id="section-{{ $section->id }}" class="form-check-input"
                                    {{ in_array($section->id, $product->sections ?? []) ? 'checked' : '' }}>
                                <label for="section-{{ $section->id }}"
                                    class="form-check-label">{{ $section->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Категории -->
                <div class="form-group">
                    <label>Категории</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (\App\Models\Category::all() as $category)
                            <div class="form-check-inline">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    id="category-{{ $category->id }}" class="form-check-input"
                                    {{ in_array($category->id, $product->categories ?? []) ? 'checked' : '' }}>
                                <label for="category-{{ $category->id }}"
                                    class="form-check-label">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Подкатегории -->
                <div class="form-group">
                    <label>Подкатегории</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (\App\Models\Subcategory::all() as $subcategory)
                            <div class="form-check-inline">
                                <input type="checkbox" name="subcategories[]" value="{{ $subcategory->id }}"
                                    id="subcategory-{{ $subcategory->id }}" class="form-check-input"
                                    {{ in_array($subcategory->id, $product->subcategories ?? []) ? 'checked' : '' }}>
                                <label for="subcategory-{{ $subcategory->id }}"
                                    class="form-check-label">{{ $subcategory->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Подподкатегории -->
                <div class="form-group">
                    <label>Подподкатегории</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (\App\Models\Subsubcategory::all() as $subsubcategory)
                            <div class="form-check-inline">
                                <input type="checkbox" name="subsubcategories[]" value="{{ $subsubcategory->id }}"
                                    id="subsubcategory-{{ $subsubcategory->id }}" class="form-check-input"
                                    {{ in_array($subsubcategory->id, $product->subsubcategories ?? []) ? 'checked' : '' }}>
                                <label for="subsubcategory-{{ $subsubcategory->id }}"
                                    class="form-check-label">{{ $subsubcategory->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Бренды -->
                <div class="form-group">
                    <label>Бренды</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (\App\Models\Brand::all() as $brand)
                            <div class="form-check-inline">
                                <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                    id="brand-{{ $brand->id }}" class="form-check-input"
                                    {{ in_array($brand->id, $product->brands ?? []) ? 'checked' : '' }}>
                                <label for="brand-{{ $brand->id }}"
                                    class="form-check-label">{{ $brand->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Ranges -->
                <div class="form-group">
                    <label>Линейка бренда</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (\App\Models\Range::all() as $range)
                            <div class="form-check-inline">
                                <input type="checkbox" name="ranges[]" value="{{ $range->name }}"
                                    id="range-{{ $range->id }}" class="form-check-input"
                                    {{ in_array($range->name, $product->ranges ?? []) ? 'checked' : '' }}>
                                <label for="range-{{ $range->id }}" class="form-check-label">{{ $range->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Фильтры -->
                <div class="form-group">
                    <label>Фильтры</label>
                    @foreach (\App\Models\Filter::all() as $filter)
                        <div class="mb-2">
                            <strong>{{ $filter->name }}</strong>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($filter->values as $value)
                                    @php
                                        $productFilters = is_string($product->filters)
                                            ? json_decode($product->filters, true)
                                            : $product->filters;
                                    @endphp
                                    <div class="form-check">
                                        <input type="checkbox" name="filters[{{ $filter->id }}][]"
                                            value="{{ $value }}"
                                            id="filter-{{ $filter->id }}-{{ $value }}" class="form-check-input"
                                            {{ isset($productFilters[$filter->id]) && is_array($productFilters[$filter->id]) && in_array($value, $productFilters[$filter->id]) ? 'checked' : '' }}>
                                        <label for="filter-{{ $filter->id }}-{{ $value }}"
                                            class="form-check-label">{{ $value }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Характеристики -->
                <div class="form-group">
                    <label>Характеристики</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach (\App\Models\Characteristic::all() as $characteristic)
                            <div class="form-group">
                                <label for="characteristic-{{ $characteristic->id }}">{{ $characteristic->name }}</label>
                                <input type="text" name="characteristics[{{ $characteristic->id }}]"
                                    id="characteristic-{{ $characteristic->id }}"
                                    value="{{ $product->characteristics[$characteristic->id] ?? '' }}"
                                    class="form-control w-full">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Кнопки -->
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('dashboard.product.details', $product->id) }}" class="btn btn-secondary">Отмена</a>
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                </div>
            </form>
        @endif
    </div>
@endsection
