<?php

namespace App\Livewire;

use App\Models\Characteristic;
use Livewire\Component;

class CharacteristicCrud extends Component
{
    public $characteristics;
    public $name;
    public $editId;

    public function mount()
    {
        $this->characteristics = Characteristic::all();
    }

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255']);
        Characteristic::create(['name' => $this->name]);
        $this->resetForm();
        $this->refreshData();
    }

    public function edit($id)
    {
        $characteristic = Characteristic::findOrFail($id);
        $this->editId = $id;
        $this->name = $characteristic->name;
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255']);
        $characteristic = Characteristic::findOrFail($this->editId);
        $characteristic->update(['name' => $this->name]);
        $this->resetForm();
        $this->refreshData();
    }

    public function delete($id)
    {
        Characteristic::findOrFail($id)->delete();
        $this->refreshData();
    }

    private function resetForm()
    {
        $this->name = null;
        $this->editId = null;
    }

    private function refreshData()
    {
        $this->characteristics = Characteristic::all();
    }

    public function render()
    {
        return view('livewire.characteristic-crud', [
            'characteristics' => $this->characteristics
        ]);
    }
}