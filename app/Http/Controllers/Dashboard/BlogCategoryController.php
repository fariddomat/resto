<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:superadministrator|blogger']);
    }

    public function index()
    {
        $categories = BlogCategory::orderBy('id', 'asc')->paginate(10);
        return view('dashboard.blogcategories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.blogcategories.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required'],
        ];
        $validatedData = $request->validate($rules);

        $blogCategory = new BlogCategory();
        $blogCategory->name = $validatedData['name'];


        $blogCategory->slug = Str::slug($request->slug, '-');

        $blogCategory->save();
        session()->flash('success', 'Blog Category Added Successfully');
        return redirect()->route('dashboard.blogcategories.index');
    }
    public function edit(BlogCategory $blogcategory)
    {
        return view('dashboard.blogcategories.edit', compact('blogcategory'));
    }

    public function update(Request $request, BlogCategory $blogcategory)
    {
        $rules = [
            'name' => ['required'],
        ];
        $validatedData = $request->validate($rules);

        $blogcategory->name = $validatedData['name'];
        $blogcategory->slug = Str::slug($request->slug, '-');

        $blogcategory->save();
        session()->flash('success', 'Blog Category Updated Successfully');
        return redirect()->route('dashboard.blogcategories.index');
    }

    public function destroy(BlogCategory $blogcategory)
    {
        $blogcategory->delete();
        session()->flash('success', 'Blog Category Deleted Successfully');
        return redirect()->route('dashboard.blogcategories.index');
    }
}
