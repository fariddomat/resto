<?php

namespace App\Livewire\BlogCategory;


use Livewire\Component;
use App\Models\BlogCategory;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';


    public function render()
    {
        $categories = BlogCategory::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'asc')
            ->paginate(10);
        return view('livewire.blog-category.index', compact('categories'));
    }
}

