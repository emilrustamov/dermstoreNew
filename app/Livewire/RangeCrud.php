<?php

namespace App\Livewire;

use App\Models\Brand; // Add this line
use App\Models\Range;
use Livewire\Component;

class RangeCrud extends Component
{
    public $ranges; // Список всех ranges
    public $brands; // Add this line
    public $name; // Название range
    public $brandIds = []; // ID выбранных брендов
    public $editId; // ID редактируемого range

    public function mount()
    {
        // Инициализация данных
        $this->ranges = Range::all();
        $this->brands = Brand::all(); // Add this line
    }

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255']);

        // Создание нового range
        Range::create([
            'name' => $this->name,
            'brands' => $this->brandIds,
        ]);

        $this->resetForm();
        $this->refreshData();
    }

    public function edit($id)
    {
        $range = Range::findOrFail($id);

        $this->editId = $id;
        $this->name = $range->name;
        $this->brandIds = $range->brands; // Получение связанных брендов
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255']);

        $range = Range::findOrFail($this->editId);

        // Обновление range
        $range->update([
            'name' => $this->name,
            'brands' => $this->brandIds,
        ]);

        $this->resetForm();
        $this->refreshData();
    }

    public function delete($id)
    {
        Range::findOrFail($id)->delete();

        $this->refreshData();
    }

    private function resetForm()
    {
        $this->name = null;
        $this->brandIds = [];
        $this->editId = null;
    }

    private function refreshData()
    {
        $this->ranges = Range::all();
        $this->brands = Brand::all(); // Add this line
    }

    public function render()
    {
        return view('livewire.range-crud', [
            'brands' => $this->brands // Add this line
        ]);
    }
}
