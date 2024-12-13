<?php

namespace App\Livewire;

use App\Models\Section;
use Livewire\Component;

class SectionCrud extends Component
{
    public $sections; // Список секций
    public $name;     // Название новой/редактируемой секции
    public $editId;   // ID секции, которую редактируем

    public function mount()
    {
        $this->sections = Section::all();
    }

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255']);
        Section::create(['name' => $this->name]);
        $this->resetForm();
        $this->refreshData();
    }

    public function edit($id)
    {
        $section = Section::findOrFail($id);
        $this->editId = $id;
        $this->name = $section->name;
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255']);
        $section = Section::findOrFail($this->editId);
        $section->update(['name' => $this->name]);
        $this->resetForm();
        $this->refreshData();
    }

    public function delete($id)
    {
        Section::findOrFail($id)->delete();
        $this->refreshData();
    }

    private function resetForm()
    {
        $this->name = null;
        $this->editId = null;
    }

    private function refreshData()
    {
        $this->sections = Section::all();
    }

    public function render()
    {
        return view('livewire.section-crud', [
            'sections' => $this->sections,
        ]);
    }
}
