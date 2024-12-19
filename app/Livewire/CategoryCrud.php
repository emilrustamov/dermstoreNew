<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Section;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use Livewire\Component;

class CategoryCrud extends Component
{
    public $categories; // Список всех категорий
    public $sections; // Список всех секций
    public $name; // Название категории
    public $sectionIds = []; // ID выбранных секций
    public $editId; // ID редактируемой категории
    public $selectedLinks = []; // Выбранные ссылки
    public $allLinks = []; // Все ссылки

    public function mount()
    {
        // Инициализация данных
        $this->categories = Category::all();
        $this->sections = Section::all();
        $this->allLinks = $this->fetchAllLinks();
    }

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255']);

        // Создание новой категории
        Category::create([
            'name' => $this->name,
            'sections' => $this->sectionIds,
            'selected_links' => implode(',', $this->selectedLinks)
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
        $this->selectedLinks = explode(',', $category->selected_links);
        $this->allLinks = $this->fetchAllLinks($id);
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255']);

        $category = Category::findOrFail($this->editId);

        // Обновление категории
        $category->update([
            'name' => $this->name,
            'sections' => $this->sectionIds,
            'selected_links' => implode(',', $this->selectedLinks)
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
        $this->selectedLinks = [];
    }

    private function refreshData()
    {
        $this->categories = Category::all();
    }

    private function fetchAllLinks($categoryId = null)
    {
        $links = [];

        if ($categoryId) {
            $subcategories = Subcategory::where('categories', 'LIKE', '%"'.(string) $categoryId.'"%')->get();
            foreach ($subcategories as $subcategory) {
                $links[] = ['id' => 'subcategory_' . $subcategory->id, 'name' => 'Subcategory: ' . $subcategory->name];
                $subsubcategories = $subcategory->subsubcategories();
                foreach ($subsubcategories as $subsubcategory) {
                    $links[] = ['id' => 'subsubcategory_' . $subsubcategory->id, 'name' => 'Subsubcategory: ' . $subsubcategory->name];
                }
            }
        }

        return $links;
    }

    public function render()
    {
        return view('livewire.category-crud', [
            'categories' => $this->categories,
        ]);
    }
}
