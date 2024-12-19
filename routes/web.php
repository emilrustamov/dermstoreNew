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
use Illuminate\Support\Facades\Storage;
use App\Livewire\CharacteristicCrud;
use App\Livewire\RangeCrud;
use App\Livewire\SubsubcategoryCrud;

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
    Route::get('/ranges', RangeCrud::class)->name('ranges');
    Route::get('/subsubcategories', SubsubcategoryCrud::class)->name('subsubcategories');

    Route::get('/characteristics', CharacteristicCrud::class)->name('characteristics');

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

    Route::get('/dashboard/subsubcategory/{subsubcategory}', function ($subsubcategory) {
        return view('dashboard', ['subsubcategoryId' => $subsubcategory]);
    })->name('dashboard.bySubsubcategory');

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
            'subsubcategories' => 'nullable|array', // Add this line
            'brands' => 'nullable|array',
            'ranges' => 'nullable|array', // Add this line
            'filters' => 'nullable|array',
            'characteristics' => 'nullable|array',
            'image' => 'nullable|image|max:1024',
        ]);

        // Обработка фильтров
        $filters = $validatedData['filters'] ?? [];
        $formattedFilters = [];
        foreach ($filters as $filterId => $values) {
            $formattedFilters[$filterId] = array_values($values); // Сохраняем только выбранные значения
        }

        // Обработка изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение, если оно существует
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // Сохраняем новое изображение
            $validatedData['image'] = $request->file('image')->store('products', 'public');
        } else {
            $validatedData['image'] = $product->image; // Сохраняем старое изображение, если новое не загружено
        }

        // Обновление товара
        $product->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'sections' => $validatedData['sections'] ?? [],
            'categories' => $validatedData['categories'] ?? [],
            'subcategories' => $validatedData['subcategories'] ?? [],
            'subsubcategories' => $validatedData['subsubcategories'] ?? [], // Add this line
            'brands' => $validatedData['brands'] ?? [],
            'ranges' => $validatedData['ranges'] ?? [], // Add this line
            'filters' => $formattedFilters,
            'characteristics' => $validatedData['characteristics'] ?? [],
            'image' => $validatedData['image'],
        ]);

        return redirect()->route('dashboard.product.details', $product->id)
            ->with('success', 'Product updated successfully!');
    })->name('dashboard.product.update');

    Route::get('/users', \App\Livewire\UserManagement::class)->name('users');

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
