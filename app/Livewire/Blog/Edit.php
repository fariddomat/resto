<?php

namespace App\Livewire\Blog;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Str;

class Edit extends Component
{
    use WithFileUploads;

    public $blog;
    public $title, $slug, $description, $introduction, $content_table, $first_paragraph, $category;
    public $image, $author_name, $author_title, $author_image;
    public $image_alt, $index_image_alt, $show_at_home, $showed;

    public function mount(Blog $blog)
{
    if (!$blog) {
        abort(404);
    }

    $this->blog = $blog;

    // Ensure all fields are set
    $this->title = $blog->title;
    $this->slug = $blog->slug;
    $this->description = $blog->description;
    $this->introduction = $blog->introduction;
    $this->content_table = $blog->content_table;
    $this->first_paragraph = $blog->first_paragraph;
    $this->category = $blog->blog_category_id;
    $this->author_name = $blog->author_name;
    $this->author_title = $blog->author_title;
    $this->image_alt = $blog->image_alt;
    $this->index_image_alt = $blog->index_image_alt;
    $this->show_at_home = $blog->show_at_home;
    $this->showed = $blog->showed;
}


    public function update()
    {
        $validatedData = $this->validate([
            'title' => 'required',
            'slug' => 'required|unique:blogs,slug,' . $this->blog->id,
            'description' => 'required',
            'introduction' => 'required',
            'content_table' => 'required',
            'first_paragraph' => 'required',
            'category' => 'required|exists:blog_categories,id',
            'author_name' => 'required',
            'author_title' => 'required',
            'author_image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
        ]);

        $this->blog->update([
            'title' => $validatedData['title'],
            'slug' => Str::slug($validatedData['slug']),
            'description' => $validatedData['description'],
            'introduction' => $validatedData['introduction'],
            'content_table' => $validatedData['content_table'],
            'first_paragraph' => $validatedData['first_paragraph'],
            'blog_category_id' => $validatedData['category'],
            'author_name' => $validatedData['author_name'],
            'author_title' => $validatedData['author_title'],
            'image_alt' => $this->image_alt,
            'index_image_alt' => $this->index_image_alt,
            'show_at_home' => $this->show_at_home,
            'showed' => $this->showed,
        ]);

        // Handle Image Uploads
        $helper = new \App\Helpers\ImageHelper;

        if ($this->image) {
            $this->blog->image = $helper->storeImageInPublicDirectory($this->image, '/photos/blogs', 800, 550);
        }
        // dd( $this->blog->image);

        if ($this->author_image) {
            $this->blog->author_image = $helper->storeImageInPublicDirectory($this->author_image, '/photos/blogs', 200, 200);
        }

        $this->blog->save();
        dd($this->blog);
        session()->flash('success', 'Blog updated successfully!');
        return $this->redirect(route('dashboard.blogs.index'), true);
    }

    public function render()
    {
        $categories = BlogCategory::all();
        return view('livewire.blog.edit', compact('categories'));
    }
}
