<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\SectionCrud;
use App\Livewire\BrandCrud;
use App\Livewire\CategoryCrud;
use App\Livewire\SubcategoryCrud;
use App\Livewire\FilterCrud;
use App\Livewire\ProductCrud;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;



Auth::routes();



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Маршруты для Livewire-компонентов
    Route::get('/sections', SectionCrud::class)->name('sections');
    Route::get('/brands', BrandCrud::class)->name('brands');
    Route::get('/categories', CategoryCrud::class)->name('categories');
    Route::get('/subcategories', SubcategoryCrud::class)->name('subcategories');
    Route::get('/filters', FilterCrud::class)->name('filters');
    Route::get('/products', ProductCrud::class)->name('products');

    // В routes/web.php
    Route::get('/dashboard/category/{category}', function ($category) {
        return view('dashboard', ['categoryId' => $category]);
    })->name('dashboard.byCategory');

    Route::get('/dashboard/subcategory/{subcategory}', function ($subcategory) {
        return view('dashboard', ['subcategoryId' => $subcategory]);
    })->name('dashboard.bySubcategory');

    Route::get('/dashboard/brand/{brand}', function ($brand) {
        return view('dashboard', ['brandId' => $brand]);
    })->name('dashboard.byBrand');
    Route::get('/dashboard/product/{id}', function ($id) {
        $product = \App\Models\Product::findOrFail($id);
        return view('product-details', compact('product'));
    })->name('dashboard.product.details');

    Route::post('/dashboard/product/{id}/update', function (\Illuminate\Http\Request $request, $id) {
        $product = \App\Models\Product::findOrFail($id);
    
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sections' => 'nullable|array',
            'categories' => 'nullable|array',
            'subcategories' => 'nullable|array',
            'brands' => 'nullable|array',
            'filters' => 'nullable|array',
        ]);
    
        // Обработка фильтров
        $filters = $validatedData['filters'] ?? [];
        $formattedFilters = [];
        foreach ($filters as $filterId => $values) {
            $formattedFilters[$filterId] = array_values($values); // Сохраняем только выбранные значения
        }
    
        // Обновление товара
        $product->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'sections' => $validatedData['sections'] ?? [],
            'categories' => $validatedData['categories'] ?? [],
            'subcategories' => $validatedData['subcategories'] ?? [],
            'brands' => $validatedData['brands'] ?? [],
            'filters' => json_encode($formattedFilters), // Преобразуем в JSON перед сохранением
        ]);
    
        return redirect()->route('dashboard.product.details', $product->id)
            ->with('success', 'Product updated successfully!');
    })->name('dashboard.product.update');
    




    Route::get('/dashboard/section/{section}', function ($section) {
        return view('dashboard', ['sectionId' => (int)$section]);
    })->name('dashboard.bySection');

    Route::get('/', function () {
        return view('dashboard');
    });
});

Route::get('/export-products', function () {
    return Excel::download(new ProductsExport, 'products.xlsx');
})->name('export.products');
