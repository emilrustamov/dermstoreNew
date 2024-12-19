<?php

namespace App\Livewire;

use App\Models\Subsubcategory;
use App\Models\Subcategory;
use Livewire\Component;

class SubsubcategoryCrud extends Component
{
    public $subsubcategories; // Список всех подподкатегорий
    public $subcategories; // Список всех подкатегорий
    public $name; // Название подподкатегории
    public $subcategoryIds = []; // ID выбранных подкатегорий
    public $editId; // ID редактируемой подподкатегории

    public function mount()
    {
        // Инициализация данных
        $this->subsubcategories = Subsubcategory::all();
        $this->subcategories = Subcategory::all();
    }

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255']);

        // Создание новой подподкатегории
        Subsubcategory::create([
            'name' => $this->name,
            'subcategories' => $this->subcategoryIds,
        ]);

        $this->resetForm();
        $this->refreshData();
    }

    public function edit($id)
    {
        $subsubcategory = Subsubcategory::findOrFail($id);

        $this->editId = $id;
        $this->name = $subsubcategory->name;
        $this->subcategoryIds = $subsubcategory->subcategories; // Получение связанных подкатегорий
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255']);

        $subsubcategory = Subsubcategory::findOrFail($this->editId);

        // Обновление подподкатегории
        $subsubcategory->update([
            'name' => $this->name,
            'subcategories' => $this->subcategoryIds,
        ]);

        $this->resetForm();
        $this->refreshData();
    }

    public function delete($id)
    {
        Subsubcategory::findOrFail($id)->delete();

        $this->refreshData();
    }

    private function resetForm()
    {
        $this->name = null;
        $this->subcategoryIds = [];
        $this->editId = null;
    }

    private function refreshData()
    {
        $this->subsubcategories = Subsubcategory::all();
    }

    public function render()
    {
        return view('livewire.subsubcategory-crud');
    }
}
