<?php

namespace App\Livewire;

use App\Models\Subcategory;
use App\Models\Category;
use Livewire\Component;

class SubcategoryCrud extends Component
{
    public $subcategories; // Список всех подкатегорий
    public $categories; // Список всех категорий
    public $name; // Название подкатегории
    public $categoryIds = []; // ID выбранных категорий
    public $editId; // ID редактируемой подкатегории

    public function mount()
    {
        // Инициализация данных
        $this->subcategories = Subcategory::all();
        $this->categories = Category::all();
    }

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255']);

        // Создание новой подкатегории
        Subcategory::create([
            'name' => $this->name,
            'categories' => $this->categoryIds,
        ]);

        $this->resetForm();
        $this->refreshData();
    }

    public function edit($id)
    {
        $subcategory = Subcategory::findOrFail($id);

        $this->editId = $id;
        $this->name = $subcategory->name;
        $this->categoryIds = $subcategory->categories; // Получение связанных категорий
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255']);

        $subcategory = Subcategory::findOrFail($this->editId);

        // Обновление подкатегории
        $subcategory->update([
            'name' => $this->name,
            'categories' => $this->categoryIds,
        ]);

        $this->resetForm();
        $this->refreshData();
    }

    public function delete($id)
    {
        Subcategory::findOrFail($id)->delete();

        $this->refreshData();
    }

    private function resetForm()
    {
        $this->name = null;
        $this->categoryIds = [];
        $this->editId = null;
    }

    private function refreshData()
    {
        $this->subcategories = Subcategory::all();
    }

    public function render()
    {
        return view('livewire.subcategory-crud');
    }
}
