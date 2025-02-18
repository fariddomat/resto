<?php

namespace App\Livewire\Blog;

use Livewire\Component;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $title, $slug, $description, $introduction, $content_table, $first_paragraph, $category, $image, $author_name, $author_title;

    public $image_alt, $index_image_alt, $show_at_home = 0, $showed = 0;

    public $author_image;

    public function store()
    {
        $validatedData = $this->validate([
            'title' => 'required',
            'slug' => 'required|unique:blogs,slug',
            'description' => 'required',
            'introduction' => 'required',
            'content_table' => 'required',
            'first_paragraph' => 'required',
            'category' => 'required|exists:blog_categories,id',
            'author_name' => 'required',
            'author_title' => 'required',
            'author_image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp',
        ]);

        $blog = Blog::create([
            'title' => $validatedData['title'],
            'slug' => Str::slug($validatedData['slug']),
            'description' => $validatedData['description'],
            'introduction' => $validatedData['introduction'],
            'content_table' => $validatedData['content_table'],
            'first_paragraph' => $validatedData['first_paragraph'],
            'blog_category_id' => $validatedData['category'],
            'author_name' => $validatedData['author_name'],
            'author_title' => $validatedData['author_title'],
            'image' => $this->image_alt,
            'image_alt' => $this->image_alt,
            'index_image_alt' => $this->index_image_alt,
            'show_at_home' => $this->show_at_home,
            'showed' => $this->showed,
        ]);

        // Handle images for author_image, image, and index_image
        $helper = new \App\Helpers\ImageHelper;
        $blog->image = $helper->storeImageInPublicDirectory($this->image, '/photos/blogs', 800, 550);
        $blog->author_image = $this->author_image ? $helper->storeImageInPublicDirectory($this->author_image, '/photos/blogs', 200, 200) : null;
        $blog->save();

        session()->flash('success', 'Blog created successfully!');
        return $this->redirect(route('dashboard.blogs.index'),true);
    }

    public function render()
    {
        $categories = BlogCategory::all();
        return view('livewire.blog.create', compact('categories'));
    }
}
