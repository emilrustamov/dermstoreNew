@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактирование товара: {{ $product->name }}</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('dashboard.product.update', $product->id) }}" method="POST">
            @csrf

            <!-- Название -->
            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" name="name" id="name" value="{{ $product->name }}" class="form-control" required>
            </div>

            <!-- Описание -->
            <div class="form-group">
                <label for="description">Описание</label>
                <textarea name="description" id="description" class="form-control" required>{{ $product->description }}</textarea>
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
                            <label for="section-{{ $section->id }}" class="form-check-label">{{ $section->name }}</label>
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

            <!-- Бренды -->
            <div class="form-group">
                <label>Бренды</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach (\App\Models\Brand::all() as $brand)
                        <div class="form-check-inline">
                            <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                id="brand-{{ $brand->id }}" class="form-check-input"
                                {{ in_array($brand->id, $product->brands ?? []) ? 'checked' : '' }}>
                            <label for="brand-{{ $brand->id }}" class="form-check-label">{{ $brand->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label>Фильтры</label>
                @foreach (\App\Models\Filter::all() as $filter)
                    <div class="mb-2">
                        <strong>{{ $filter->name }}</strong>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($filter->values as $value)
                                @php
                                    // Проверяем и декодируем данные фильтра для корректной работы in_array
                                    $productFilters = is_string($product->filters) ? json_decode($product->filters, true) : $product->filters;
                                @endphp
                                <div class="form-check">
                                    <input type="checkbox" 
                                           name="filters[{{ $filter->id }}][]" 
                                           value="{{ $value }}" 
                                           id="filter-{{ $filter->id }}-{{ $value }}" 
                                           class="form-check-input"
                                           {{ isset($productFilters[$filter->id]) && is_array($productFilters[$filter->id]) && in_array($value, $productFilters[$filter->id]) ? 'checked' : '' }}>
                                    <label for="filter-{{ $filter->id }}-{{ $value }}" class="form-check-label">{{ $value }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            
            


            <!-- Кнопки -->
            <div class="d-flex justify-content-between mt-3">

                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            </div>
        </form>
    </div>
@endsection
