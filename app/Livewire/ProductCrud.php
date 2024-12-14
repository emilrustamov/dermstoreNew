<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Filter;
use App\Models\Product;
use App\Models\Section;
use App\Models\Subcategory;
use Livewire\Component;
use Livewire\WithFileUploads;


class ProductCrud extends Component
{
    use WithFileUploads;
    public $products;
    public $sections;
    public $categories;
    public $subcategories;
    public $brands;
    public $filters;
    public $image;
    public $currentImage; 
    public $name;
    public $description;
    public $sectionIds = [];
    public $categoryIds = [];
    public $subcategoryIds = [];
    public $brandIds = [];
    public $filterIds = [];
    public $editId;
    public $filterValues = [];

    public function mount( $category = null, $subcategory = null, $brand = null)
    {
        // Инициализация данных для создания товара
        $this->products = Product::all();
        $this->sections = Section::all();
        $this->categories = Category::all();
        $this->subcategories = Subcategory::all();
        $this->brands = Brand::all();
        $this->filters = Filter::all();

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
            $formattedFilters[$filterId] = array_keys(array_filter($values)); // Оставляем только выбранные значения
        }

        Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'sections' => $this->sectionIds,
            'categories' => $this->categoryIds,
            'subcategories' => $this->subcategoryIds,
            'brands' => $this->brandIds,
            'filters' => json_encode($formattedFilters), 
            'image' => $imagePath, 
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

        $this->sectionIds = is_array($product->sections) ? $product->sections : json_decode($product->sections, true);
        $this->categoryIds = is_array($product->categories) ? $product->categories : json_decode($product->categories, true);
        $this->subcategoryIds = is_array($product->subcategories) ? $product->subcategories : json_decode($product->subcategories, true);
        $this->brandIds = is_array($product->brands) ? $product->brands : json_decode($product->brands, true);
        $this->currentImage = $product->image; 
        // Разбираем фильтры
        $filters = is_array($product->filters) ? $product->filters : json_decode($product->filters, true);
        if (!is_array($filters)) {
            $filters = [];
        }

        // Приводим фильтры к структуре, понятной для чекбоксов
        foreach ($filters as $filterId => $values) {
            foreach ($values as $value) {
                $this->filterValues[$filterId][$value] = true;
            }
        }
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
            $formattedFilters[$filterId] = array_keys(array_filter($values));
        }

        $product->update([
            'name' => $this->name,
            'description' => $this->description,
            'sections' => $this->sectionIds,
            'categories' => $this->categoryIds,
            'subcategories' => $this->subcategoryIds,
            'brands' => $this->brandIds,
            'filters' => json_encode($formattedFilters),
            'image' => $imagePath,
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
        $this->brandIds = [];
        $this->filterValues = [];
        $this->image = null;
        $this->currentImage = null;
        $this->editId = null;
    }

    private function refreshData()
    {
        $this->products = Product::all();
    }

    public function render()
    {
        return view('livewire.product-crud');
    }
}
