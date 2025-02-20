<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SaleCategory;
use Illuminate\Http\Request;

class SaleCategoryController extends Controller
{
    // عرض جميع الفئات
    public function index()
    {
        $categories = SaleCategory::withCount('saleItems')->paginate(02);
        return view('dashboard.sale_categories.index', compact('categories'));
    }

    // عرض صفحة إضافة فئة جديدة
    public function create()
    {
        return view('dashboard.sale_categories.create');
    }

    // تخزين فئة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        SaleCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('dashboard.sale_categories.index')
            ->with('success', 'Sale category created successfully.');
    }

    // عرض تفاصيل فئة معينة
    public function show($id)
    {
        $saleCategory = SaleCategory::findOrFail($id);
        return view('dashboard.sale_categories.show', compact('saleCategory'));
    }

    // عرض صفحة تعديل الفئة
    public function edit($id)
    {
        $saleCategory = SaleCategory::findOrFail($id);
        return view('dashboard.sale_categories.edit', compact('saleCategory'));
    }

    // تحديث الفئة
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $saleCategory = SaleCategory::findOrFail($id);

        $saleCategory->update([
            'name' => $request->name,
        ]);

        return redirect()->route('dashboard.sale_categories.index')
            ->with('success', 'Sale category updated successfully.');
    }

    // حذف الفئة
    public function destroy($id)
    {
        $saleCategory = SaleCategory::findOrFail($id);
        $saleCategory->delete();

        return redirect()->route('dashboard.sale_categories.index')
            ->with('success', 'Sale category deleted successfully.');
    }
}
