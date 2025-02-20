<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PurchaseCategory;
use Illuminate\Http\Request;

class PurchaseCategoryController extends Controller
{
    // عرض جميع التصنيفات
    public function index()
    {
        $categories = PurchaseCategory::withCount('purchaseItems')->paginate(20);
        return view('dashboard.purchase_categories.index', compact('categories'));
    }

    // عرض صفحة إضافة تصنيف جديد
    public function create()
    {
        return view('dashboard.purchase_categories.create');
    }

    // تخزين تصنيف جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        PurchaseCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('dashboard.purchase_categories.index')
            ->with('success', 'Category created successfully.');
    }

    // عرض تفاصيل التصنيف
    public function show($id)
    {
        $category = PurchaseCategory::findOrFail($id);
        return view('dashboard.purchase_categories.show', compact('category'));
    }

    // عرض صفحة تعديل التصنيف
    public function edit($id)
    {
        $category = PurchaseCategory::findOrFail($id);
        return view('dashboard.purchase_categories.edit', compact('category'));
    }

    // تحديث التصنيف
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = PurchaseCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('dashboard.purchase_categories.index')
            ->with('success', 'Category updated successfully.');
    }

    // حذف التصنيف
    public function destroy($id)
    {
        $category = PurchaseCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('dashboard.purchase_categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
