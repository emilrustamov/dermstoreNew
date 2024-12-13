<?php

namespace App\Livewire;
use App\Models\Filter;
use Livewire\Component;

class FilterCrud extends Component
{
    public $filters;
    public $name;
    public $values;
    public $editId;

    public function mount()
    {
        $this->filters = Filter::all();
    }

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255', 'values' => 'required|string']);
        Filter::create(['name' => $this->name, 'values' => explode(',', $this->values)]);
        $this->resetForm();
        $this->refreshData();
    }

    public function edit($id)
    {
        $filter = Filter::findOrFail($id);
        $this->editId = $id;
        $this->name = $filter->name;
        $this->values = implode(',', $filter->values);
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255', 'values' => 'required|string']);
        $filter = Filter::findOrFail($this->editId);
        $filter->update(['name' => $this->name, 'values' => explode(',', $this->values)]);
        $this->resetForm();
        $this->refreshData();
    }

    public function delete($id)
    {
        Filter::findOrFail($id)->delete();
        $this->refreshData();
    }

    private function resetForm()
    {
        $this->name = null;
        $this->values = null;
        $this->editId = null;
    }

    private function refreshData()
    {
        $this->filters = Filter::all();
    }

    public function render()
    {
        return view('livewire.filter-crud');
    }
}

