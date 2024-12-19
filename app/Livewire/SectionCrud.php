<?php

namespace App\Livewire;

use App\Models\Section;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use Livewire\Component;

class SectionCrud extends Component
{
    public $sections; // Список секций
    public $name;     // Название новой/редактируемой секции
    public $editId;   // ID секции, которую редактируем
    public $selectedLinks = [];
    public $allLinks = [];

    public function mount()
    {
        $this->sections = Section::all();
        $this->allLinks = $this->fetchAllLinks();
    }

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255']);
        Section::create(['name' => $this->name, 'selected_links' => implode(',', $this->selectedLinks)]);
        $this->resetForm();
        $this->refreshData();
    }

    public function edit($id)
    {
        $section = Section::findOrFail($id);
        $this->editId = $id;
        $this->name = $section->name;
        $this->selectedLinks = explode(',', $section->selected_links);
        $this->allLinks = $this->fetchAllLinks($id);
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255']);
        $section = Section::findOrFail($this->editId);
        $section->update(['name' => $this->name, 'selected_links' => implode(',', $this->selectedLinks)]);
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
        $this->selectedLinks = [];
    }

    private function refreshData()
    {
        $this->sections = Section::all();
    }

    private function fetchAllLinks($sectionId = null)
    {
        $links = [];

        if ($sectionId) {
            $categories = Category::where('sections', 'LIKE', '%"'.(string) $sectionId.'"%')->get();
            foreach ($categories as $category) {
                $links[] = ['id' => 'category_' . $category->id, 'name' => 'Category: ' . $category->name];
                $subcategories = $category->subcategories();
                foreach ($subcategories as $subcategory) {
                    $links[] = ['id' => 'subcategory_' . $subcategory->id, 'name' => 'Subcategory: ' . $subcategory->name];
                    $subsubcategories = $subcategory->subsubcategories();
                    foreach ($subsubcategories as $subsubcategory) {
                        $links[] = ['id' => 'subsubcategory_' . $subsubcategory->id, 'name' => 'Subsubcategory: ' . $subsubcategory->name];
                    }
                }
            }
        }

        return $links;
    }

    public function render()
    {
        return view('livewire.section-crud', [
            'sections' => $this->sections,
        ]);
    }
}
