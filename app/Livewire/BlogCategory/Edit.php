<?php

namespace App\Livewire\BlogCategory;

use Livewire\Component;
use App\Models\BlogCategory;
use Illuminate\Support\Str;

class Edit extends Component
{
    public $categoryId;
    public $name;
    public $slug;

    // Mount method to load the existing data
    public function mount(BlogCategory $blogcategory)
    {
        // Set the initial values for the fields
        $this->categoryId = $blogcategory->id;
        $this->name = $blogcategory->name;
        $this->slug = $blogcategory->slug;
    }

    // Method to handle updating the BlogCategory
    public function updateCategory()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:blog_categories,slug,' . $this->categoryId,
        ]);
        // Find the BlogCategory and update it
        $blogCategory = BlogCategory::findOrFail($this->categoryId);
        $blogCategory->name = $this->name;
        $blogCategory->slug = Str::slug($this->slug ?? $this->name);
        $blogCategory->save();

        // Flash a success message and reset form data
        session()->flash('message', 'Blog Category Updated Successfully');

        // Redirect to the index page (Blog Category list)
        return $this->redirect(route('dashboard.blogcategories.index'), true);
    }

    public function render()
    {
        return view('livewire.blog-category.edit');
    }
}
