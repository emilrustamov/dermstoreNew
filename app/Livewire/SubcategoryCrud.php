<?php

namespace App\Livewire;

use App\Models\Subcategory;
use App\Models\Category;
use App\Models\Subsubcategory;
use Livewire\Component;

class SubcategoryCrud extends Component
{
    public $subcategories; // Список всех подкатегорий
    public $categories; // Список всех категорий
    public $name; // Название подкатегории
    public $categoryIds = []; // ID выбранных категорий
    public $editId; // ID редактируемой подкатегории
    public $selectedLinks = []; // Выбранные ссылки
    public $allLinks = []; // Все ссылки

    public function mount()
    {
        // Инициализация данных
        $this->subcategories = Subcategory::all();
        $this->categories = Category::all();
        $this->allLinks = $this->fetchAllLinks();
    }

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255']);

        // Создание новой подкатегории
        Subcategory::create([
            'name' => $this->name,
            'categories' => $this->categoryIds,
            'selected_links' => implode(',', $this->selectedLinks)
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
        $this->selectedLinks = explode(',', $subcategory->selected_links); // Получение выбранных ссылок
        $this->allLinks = $this->fetchAllLinks($id); // Получение всех ссылок
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255']);

        $subcategory = Subcategory::findOrFail($this->editId);

        // Обновление подкатегории
        $subcategory->update([
            'name' => $this->name,
            'categories' => $this->categoryIds,
            'selected_links' => implode(',', $this->selectedLinks)
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
        $this->selectedLinks = [];
    }

    private function refreshData()
    {
        $this->subcategories = Subcategory::all();
    }

    private function fetchAllLinks($subcategoryId = null)
    {
        $links = [];

        if ($subcategoryId) {
            $subsubcategories = Subsubcategory::where('subcategories', 'LIKE', '%"'.(string) $subcategoryId.'"%')->get();
            foreach ($subsubcategories as $subsubcategory) {
                $links[] = ['id' => 'subsubcategory_' . $subsubcategory->id, 'name' => 'Subsubcategory: ' . $subsubcategory->name];
            }
        }

        return $links;
    }

    public function render()
    {
        return view('livewire.subcategory-crud', [
            'subcategories' => $this->subcategories,
        ]);
    }
}
