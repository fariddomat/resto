<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:superadministrator|blogger']);
    }

    public function index(Request $request)
    {
        $blogs = Blog::whenSearch($request->search)->with(['category'])->get();
        return view('dashboard.blogs.index', compact('blogs'));
    }
    public function create()
    {
        $categories = BlogCategory::all();
        return view('dashboard.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => ['required'],
            'slug' => ['required', 'unique:blogs,slug'],
            'description' => ['required'],
            'introduction' => ['required'],
            'content_table' => ['required'],
            'first_paragraph' => ['required'],
            'category' => ['required', 'exists:blog_categories,id'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp'],
            'showed' => ['nullable'],
            'image_alt' => ['nullable'],
            'index_image_alt' => ['nullable'],
            'show_at_home' => ['nullable'],
            'author_name' => ['required'],
            'author_title' => ['required'],
            'author_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp'],
        ];
        $validatedData = $request->validate($rules);

        $blog = new Blog();
        $blog->title = $validatedData['title'];
        $blog->description = $validatedData['description'];
        $blog->introduction = $validatedData['introduction'];
        $blog->content_table = $validatedData['content_table'];
        $blog->first_paragraph = $validatedData['first_paragraph'];
        $blog->blog_category_id = $validatedData['category'];
        $blog->author_name = $validatedData['author_name'];
        $blog->author_title = $validatedData['author_title'];

        $blog->image_alt = $validatedData['image_alt'];
        $blog->index_image_alt = $validatedData['index_image_alt'];

        if ($request->has('author_image')) {
            $helper = new ImageHelper;
            $image = $request->file('author_image');
            $directory = '/photos/blogs';
            $fullPath = $helper->storeImageInPublicDirectory($image, $directory, 200, 200);
            $blog->author_image = $fullPath;
        }


        $helper = new ImageHelper;
        $image = $request->file('image');
        $directory = '/photos/blogs';
        $fullPath = $helper->storeImageInPublicDirectory($image, $directory, 800, 550);
        $blog->image = $fullPath;


        if ($request->has('index_image')) {
            $image = $request->file('index_image');
            $directory = '/photos/blogs';
            $fullPath = $helper->storeImageInPublicDirectory($image, $directory, 800, 500);
            $blog->index_image = $fullPath;
        }

        $blog->showed  = $request->has('showed') ? 1 : 0;
        $blog->show_at_home  = $request->has('show_at_home') ? 1 : 0;
        $blog->slug = Str::slug($validatedData['slug'], '-');
        $blog->save();


        session()->flash('success', 'Blog Added Successfully');

        return redirect()->route('dashboard.blogs.index');
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::all();
        return view('dashboard.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {

        $rules = [
            'title' => ['required'],
            'slug' => [
                'required',
                Rule::unique('blogs', 'slug')->ignore($blog->id)
            ],
            'description' => ['required'],
            'introduction' => ['required'],
            'content_table' => ['required'],
            'first_paragraph' => ['required'],
            'category' => ['required', 'exists:blog_categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp'],
            'showed' => ['nullable'],
            'show_at_home' => ['nullable'],
            'author_name' => ['required'],
            'author_title' => ['required'],
            'author_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp'],

            'image_alt' => ['nullable'],
            'index_image_alt' => ['nullable'],

        ];
        $validatedData = $request->validate($rules);


        $blog->title = $validatedData['title'];
        $blog->description = $validatedData['description'];
        $blog->introduction = $validatedData['introduction'];
        $blog->content_table = $validatedData['content_table'];
        $blog->first_paragraph = $validatedData['first_paragraph'];
        $blog->blog_category_id = $validatedData['category'];
        $blog->author_name = $validatedData['author_name'];
        $blog->author_title = $validatedData['author_title'];


        $blog->image_alt = $validatedData['image_alt'];
        $blog->index_image_alt = $validatedData['index_image_alt'];

        if ($request->has('author_image')) {
            $helper = new ImageHelper;
            $helper->removeImageInPublicDirectory($blog->author_image);
            $image = $request->file('author_image');
            $directory = '/photos/blogs';
            $fullPath = $helper->storeImageInPublicDirectory($image, $directory, 200, 200);
            $blog->author_image = $fullPath;
        }

            $helper = new ImageHelper;
        if ($request->has('image')) {
            // Storage::disk('local')->delete($blog->image);

            $helper->removeImageInPublicDirectory($blog->image);
            $image = $request->file('image');
            $directory = '/photos/blogs';
            $fullPath = $helper->storeImageInPublicDirectory($image, $directory, 800, 500);
            $blog->image = $fullPath;
        }
        if ($request->has('index_image')) {

            // $helper->removeImageInPublicDirectory($blog->index_image);
            $image = $request->file('index_image');
            $directory = '/photos/blogs';
            $fullPath = $helper->storeImageInPublicDirectory($image, $directory, 800, 500);
            $blog->index_image = $fullPath;
        }
        $blog->showed  = $request->has('showed') ? 1 : 0;
        $blog->show_at_home  = $request->has('show_at_home') ? 1 : 0;
        $blog->slug = Str::slug($validatedData['slug'], '-');
        $blog->save();
        session()->flash('success', 'Blog Updated Successfully');
        return redirect()->route('dashboard.blogs.index');
    }


    public function destroy(Blog $blog)
    {
        $blog->delete();
        session()->flash('success', 'Blog Deleted Successfully');
        return redirect()->route('dashboard.blogs.index');
    }


    public function destroyIndexImage(Blog $blog)
    {
        if ($blog->index_image) {
            $helper = new ImageHelper;
            $helper->removeImageInPublicDirectory($blog->image);
        }
    }
}
