<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Section;
use Livewire\Component;

class CategoryCrud extends Component
{
    public $categories; // Список всех категорий
    public $sections; // Список всех секций
    public $name; // Название категории
    public $sectionIds = []; // ID выбранных секций
    public $editId; // ID редактируемой категории

    public function mount()
    {
        // Инициализация данных
        $this->categories = Category::all();
        $this->sections = Section::all();
    }

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255']);

        // Создание новой категории
        Category::create([
            'name' => $this->name,
            'sections' => $this->sectionIds,
        ]);

        $this->resetForm();
        $this->refreshData();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $this->editId = $id;
        $this->name = $category->name;
        $this->sectionIds = $category->sections; // Получение связанных секций
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255']);

        $category = Category::findOrFail($this->editId);

        // Обновление категории
        $category->update([
            'name' => $this->name,
            'sections' => $this->sectionIds,
        ]);

        $this->resetForm();
        $this->refreshData();
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();

        $this->refreshData();
    }

    private function resetForm()
    {
        $this->name = null;
        $this->sectionIds = [];
        $this->editId = null;
    }

    private function refreshData()
    {
        $this->categories = Category::all();
    }

    public function render()
    {
        return view('livewire.category-crud');
    }
}
