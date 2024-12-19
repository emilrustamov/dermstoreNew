<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Characteristic;
use App\Models\Filter;
use App\Models\Product;
use App\Models\Range;
use App\Models\Section;
use App\Models\Subcategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Subsubcategory;

class ProductCrud extends Component
{
    use WithFileUploads;
    public $products;
    public $sections;
    public $categories;
    public $subcategories;
    public $subsubcategories;
    public $brands;
    public $filters;
    public $ranges;
    public $characteristics;
    public $image;
    public $currentImage; 
    public $name;
    public $description;
    public $sectionIds = [];
    public $categoryIds = [];
    public $subcategoryIds = [];
    public $subsubcategoryIds = [];
    public $brandIds = [];
    public $rangeIds = [];
    public $filterIds = [];
    public $editId;
    public $filterValues = [];
    public $characteristicValues = [];

    public function mount($category = null, $subcategory = null, $brand = null)
    {
        // Инициализация данных для создания товара
        $this->products = Product::all();
        $this->sections = Section::all();
        $this->categories = Category::all();
        $this->subcategories = Subcategory::all();
        $this->subsubcategories = Subsubcategory::all();
        $this->brands = Brand::all();
        $this->filters = Filter::all();
        $this->ranges = Range::all();
        $this->characteristics = Characteristic::all();

        // Фильтрация по категориям, подкатегориям и брендам
        if ($category) {
            $this->products = Product::whereJsonContains('categories', $category)->get();
        }

        if ($subcategory) {
            $this->products = Product::whereJsonContains('subcategories', $subcategory)->get();
        }

        if ($brand) {
            $this->products = Product::whereJsonContains('brands', $brand)->get();
        }
    }

    public function create()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:1024',
        ]);

        $imagePath = $this->image ? $this->image->store('products', 'public') : null;

        // Преобразуем фильтры в формат: ["filter_id": ["value1", "value2"]]
        $formattedFilters = [];
        foreach ($this->filterValues as $filterId => $values) {
            $formattedFilters[$filterId] = array_values($values); // Оставляем только выбранные значения
        }

        // Получаем названия диапазонов
        $rangeNames = Range::whereIn('id', $this->rangeIds)->pluck('name')->toArray();

        Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'sections' => $this->sectionIds,
            'categories' => $this->categoryIds,
            'subcategories' => $this->subcategoryIds,
            'subsubcategories' => $this->subsubcategoryIds,
            'brands' => $this->brandIds,
            'ranges' => $rangeNames, // Store range names
            'filters' => $formattedFilters, // Store filters as array
            'image' => $imagePath,
            'characteristics' => $this->characteristicValues,
        ]);

        $this->resetForm();
        $this->refreshData();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->editId = $id;
        $this->name = $product->name;
        $this->description = $product->description;

        $this->sectionIds = $product->sections;
        $this->categoryIds = $product->categories;
        $this->subcategoryIds = $product->subcategories;
        $this->subsubcategoryIds = $product->subsubcategories;
        $this->brandIds = $product->brands;
        $this->rangeIds = Range::whereIn('name', $product->ranges)->pluck('id')->toArray(); // Convert range names back to IDs
        $this->currentImage = $product->image; 
        // Разбираем фильтры
        $filters = is_array($product->filters) ? $product->filters : [];
        if (!is_array($filters)) {
            $filters = [];
        }

        // Приводим фильтры к структуре, понятной для чекбоксов
        foreach ($filters as $filterId => $values) {
            foreach ($values as $value) {
                $this->filterValues[$filterId][] = $value;
            }
        }

        $this->characteristicValues = $product->characteristics;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:1024',
        ]);

        $product = Product::findOrFail($this->editId);

        $imagePath = $this->image ? $this->image->store('products', 'public') : $this->currentImage;

        $formattedFilters = [];
        foreach ($this->filterValues as $filterId => $values) {
            $formattedFilters[$filterId] = array_values($values);
        }

        // Получаем названия диапазонов
        $rangeNames = Range::whereIn('id', $this->rangeIds)->pluck('name')->toArray();

        $product->update([
            'name' => $this->name,
            'description' => $this->description,
            'sections' => $this->sectionIds,
            'categories' => $this->categoryIds,
            'subcategories' => $this->subcategoryIds,
            'subsubcategories' => $this->subsubcategoryIds,
            'brands' => $this->brandIds,
            'ranges' => $rangeNames, // Store range names
            'filters' => $formattedFilters, // Store filters as array
            'image' => $imagePath,
            'characteristics' => $this->characteristicValues,
        ]);

        $this->resetForm();
        $this->refreshData();
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        $this->refreshData();
    }

    private function resetForm()
    {
        $this->name = null;
        $this->description = null;
        $this->sectionIds = [];
        $this->categoryIds = [];
        $this->subcategoryIds = [];
        $this->subsubcategoryIds = [];
        $this->brandIds = [];
        $this->rangeIds = [];
        $this->filterValues = [];
        $this->image = null;
        $this->currentImage = null;
        $this->editId = null;
        $this->characteristicValues = [];
    }

    private function refreshData()
    {
        $this->products = Product::all();
    }

    public function render()
    {
        return view('livewire.product-crud', [
            'characteristics' => $this->characteristics
        ]);
    }
}
