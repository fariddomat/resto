<?php

namespace App\Livewire\Blog;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Blog;
use App\Models\BlogCategory;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $category = null;

    protected $queryString = ['search', 'category'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $blogs = Blog::when($this->search, function ($query) {
            return $query->where('title', 'like', '%' . $this->search . '%')
                         ->orWhere('slug', 'like', '%' . $this->search . '%');
        })
        ->when($this->category, function ($query) {
            return $query->where('blog_category_id', $this->category);
        })
        ->with('category')
        ->paginate(10);

        $categories = BlogCategory::all();

        return view('livewire.blog.index', compact('blogs', 'categories'));
    }
}
