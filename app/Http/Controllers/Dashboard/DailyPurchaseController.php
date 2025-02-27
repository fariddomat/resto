<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DailyPurchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;

class DailyPurchaseController extends Controller
{
    // عرض جميع المشتريات اليومية
    public function index()
    {
        $dailyPurchases = DailyPurchase::with('purchaseItem')->paginate(20);
        return view('dashboard.daily_purchases.index', compact('dailyPurchases'));
    }

    // عرض صفحة إضافة شراء يومي جديد
    public function create()
    {
        $purchaseItems = PurchaseItem::all(); // استرجاع جميع العناصر التي يمكن شراؤها
        return view('dashboard.daily_purchases.create', compact('purchaseItems'));
    }

    // تخزين شراء يومي جديد
    public function store(Request $request)
    {
        $request->validate([
            'purchase_date' => 'required|date',
            'purchase_item_id' => 'required|array',
            'purchase_item_id.*' => 'exists:purchase_items,id',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
            'total_price' => 'required|array',
            'total_price.*' => 'numeric|min:0',
            'is_taxable' => 'nullable|array',
            'is_taxable.*' => 'boolean',
            'tax_rate' => 'nullable|array',
            'tax_rate.*' => 'numeric|min:0|max:100',
        ]);

        foreach ($request->purchase_item_id as $index => $item_id) {
            $tax = 0;
            if ($request->is_taxable[$index] && !empty($request->tax_rate[$index])) {
                $tax = ($request->total_price[$index] * $request->tax_rate[$index]) / 100;
            }

            DailyPurchase::create([
                'purchase_item_id' => $item_id,
                'quantity' => $request->quantity[$index],
                'total_price' => $request->total_price[$index],
                'is_taxable' => $request->is_taxable[$index],
                'tax_rate' => $request->tax_rate[$index] ?? 0,
                'total_tax' => $tax,
                'purchase_date' => $request->purchase_date,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Purchases added successfully']);
    }


    // عرض تفاصيل الشراء اليومي
    public function show($id)
    {
        $dailyPurchase = DailyPurchase::findOrFail($id);
        return view('dashboard.daily_purchases.show', compact('dailyPurchase'));
    }

    // عرض صفحة تعديل الشراء اليومي
    public function edit($id)
    {
        $dailyPurchase = DailyPurchase::findOrFail($id);
        $purchaseItems = PurchaseItem::all();
        return view('dashboard.daily_purchases.edit', compact('dailyPurchase', 'purchaseItems'));
    }

    // تحديث الشراء اليومي
    public function update(Request $request, $id)
    {
        $request->validate([
            'purchase_item_id' => 'required|exists:purchase_items,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'is_taxable' => 'nullable|boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'total_tax' => 'nullable|numeric|min:0',
        ]);

        $dailyPurchase = DailyPurchase::findOrFail($id);

        // حساب الضريبة إذا كانت قابلة للتطبيق
        $tax = 0;
        if ($request->is_taxable && $request->tax_rate) {
            $tax = ($request->total_price * $request->tax_rate) / 100;
        }

        $dailyPurchase->update([
            'purchase_item_id' => $request->purchase_item_id,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'is_taxable' => $request->is_taxable,
            'tax_rate' => $request->tax_rate,
            'total_tax' => $tax,
            'purchase_date' => $request->purchase_date,
        ]);

        return redirect()->route('dashboard.daily_purchases.index')
            ->with('success', 'Daily purchase updated successfully.');
    }

    // حذف الشراء اليومي
    public function destroy($id)
    {
        $dailyPurchase = DailyPurchase::findOrFail($id);
        $dailyPurchase->delete();

        return redirect()->route('dashboard.daily_purchases.index')
            ->with('success', 'Daily purchase deleted successfully.');
    }
}
