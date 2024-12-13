<?php

namespace App\Livewire;

use App\Models\Brand;
use Livewire\Component;

class BrandCrud extends Component
{
    public $brands;
    public $name;
    public $editId;

    public function mount()
    {
        $this->brands = Brand::all();
    }

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255']);
        Brand::create(['name' => $this->name]);
        $this->resetForm();
        $this->refreshData();
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        $this->editId = $id;
        $this->name = $brand->name;
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255']);
        $brand = Brand::findOrFail($this->editId);
        $brand->update(['name' => $this->name]);
        $this->resetForm();
        $this->refreshData();
    }

    public function delete($id)
    {
        Brand::findOrFail($id)->delete();
        $this->refreshData();
    }

    private function resetForm()
    {
        $this->name = null;
        $this->editId = null;
    }

    private function refreshData()
    {
        $this->brands = Brand::all();
    }

    public function render()
    {
        return view('livewire.brand-crud');
    }
}
