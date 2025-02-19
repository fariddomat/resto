<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PurchaseItem;
use App\Models\PurchaseCategory;
use Illuminate\Http\Request;

class PurchaseItemController extends Controller
{
    // عرض جميع العناصر
    public function index()
    {
        $items = PurchaseItem::with('purchaseCategory')->paginate(20);
        return view('dashboard.purchase_items.index', compact('items'));
    }

    // عرض صفحة إضافة عنصر جديد
    public function create()
    {
        $categories = PurchaseCategory::all(); // استرجاع جميع التصنيفات
        return view('dashboard.purchase_items.create', compact('categories'));
    }

    // تخزين عنصر جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'purchase_category_id' => 'required|exists:purchase_categories,id',
        ]);

        PurchaseItem::create([
            'name' => $request->name,
            'price' => $request->price,
            'purchase_category_id' => $request->purchase_category_id,
        ]);

        return redirect()->route('dashboard.purchase_items.index')
            ->with('success', 'Item created successfully.');
    }

    // عرض تفاصيل العنصر
    public function show($id)
    {
        $item = PurchaseItem::findOrFail($id);
        return view('dashboard.purchase_items.show', compact('item'));
    }

    // عرض صفحة تعديل العنصر
    public function edit($id)
    {
        $item = PurchaseItem::findOrFail($id);
        $categories = PurchaseCategory::all();
        return view('dashboard.purchase_items.edit', compact('item', 'categories'));
    }

    // تحديث العنصر
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'purchase_category_id' => 'required|exists:purchase_categories,id',
        ]);

        $item = PurchaseItem::findOrFail($id);
        $item->update([
            'name' => $request->name,
            'price' => $request->price,
            'purchase_category_id' => $request->purchase_category_id,
        ]);

        return redirect()->route('dashboard.purchase_items.index')
            ->with('success', 'Item updated successfully.');
    }

    // حذف العنصر
    public function destroy($id)
    {
        $item = PurchaseItem::findOrFail($id);
        $item->delete();

        return redirect()->route('dashboard.purchase_items.index')
            ->with('success', 'Item deleted successfully.');
    }
}
