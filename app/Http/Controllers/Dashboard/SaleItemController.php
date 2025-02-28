<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SaleItem;
use App\Models\SaleCategory;
use Illuminate\Http\Request;

class SaleItemController extends Controller
{
    // عرض جميع العناصر
    public function index()
    {
        $items = SaleItem::with('saleCategory')->paginate(20);
        return view('dashboard.sale_items.index', compact('items'));
    }

    // عرض صفحة إضافة عنصر جديد
    public function create()
    {
        $saleCategories = SaleCategory::all();
        return view('dashboard.sale_items.create', compact('saleCategories'));
    }

    // تخزين عنصر جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'sale_category_id' => 'required|exists:sale_categories,id',
        ]);

        SaleItem::create([
            'name' => $request->name,
            'price' => $request->price,
            'sale_category_id' => $request->sale_category_id,
        ]);

        return redirect()->route('dashboard.sale_items.index')
            ->with('success', 'Sale item created successfully.');
    }

    // عرض تفاصيل عنصر معين
    public function show($id)
    {
        $saleItem = SaleItem::findOrFail($id);
        return view('dashboard.sale_items.show', compact('saleItem'));
    }

    // عرض صفحة تعديل العنصر
    public function edit($id)
    {
        $saleItem = SaleItem::findOrFail($id);
        $saleCategories = SaleCategory::all();
        return view('dashboard.sale_items.edit', compact('saleItem', 'saleCategories'));
    }

    // تحديث العنصر
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'sale_category_id' => 'required|exists:sale_categories,id',
        ]);

        $saleItem = SaleItem::findOrFail($id);

        $saleItem->update([
            'name' => $request->name,
            'price' => $request->price,
            'sale_category_id' => $request->sale_category_id,
        ]);

        return redirect()->route('dashboard.sale_items.index')
            ->with('success', 'Sale item updated successfully.');
    }

    // حذف العنصر
    public function destroy($id)
    {
        $saleItem = SaleItem::findOrFail($id);
        $saleItem->delete();

        return redirect()->route('dashboard.sale_items.index')
            ->with('success', 'Sale item deleted successfully.');
    }
}
