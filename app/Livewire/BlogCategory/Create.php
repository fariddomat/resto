<?php

namespace App\Livewire\BlogCategory;

use Livewire\Component;
use App\Models\BlogCategory;
use Illuminate\Support\Str;

class Create extends Component
{
    public $name = '';
    public $slug = '';

    // Define validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|unique:blog_categories,slug',
    ];

    // Method to handle saving the BlogCategory
    public function saveCategory()
    {
        $this->validate(); // Validate form input

        // Create a new BlogCategory
        // Check if slug is provided, if not, generate it from the name
        $slug = $this->slug ?: Str::slug($this->name);

        // Create a new BlogCategory
        $blogCategory = new BlogCategory();
        $blogCategory->name = $this->name;
        $blogCategory->slug = $slug; // Use the generated or provided slug
        $blogCategory->save();

        // Flash a success message and reset form data
        session()->flash('message', 'Blog Category Created Successfully');
        $this->reset(); // Reset the form data

        // Redirect to the index page (Blog Category list)
        return $this->redirect(route('dashboard.blogcategories.index'), true);
    }

    public function render()
    {
        return view('livewire.blog-category.create');
    }
}
