<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DailySale;
use App\Models\SaleItem;
use Illuminate\Http\Request;

class DailySaleController extends Controller
{
    // عرض جميع المبيعات اليومية
    public function index()
    {
        $dailySales = DailySale::with('saleItem')->paginate(02);
        return view('dashboard.daily_sales.index', compact('dailySales'));
    }

    // عرض صفحة إضافة بيع يومي جديد
    public function create()
    {
        $saleItems = SaleItem::all();
        return view('dashboard.daily_sales.create', compact('saleItems'));
    }

    // تخزين بيع يومي جديد
    public function store(Request $request)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'sale_item_id' => 'required|array',
            'sale_item_id.*' => 'exists:sale_items,id',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
            'total_price' => 'required|array',
            'total_price.*' => 'numeric|min:0',
            'is_taxable' => 'required|array',
            'is_taxable.*' => 'nullable|boolean',
            'tax_rate' => 'nullable|array',
            'tax_rate.*' => 'numeric|min:0|max:100',
        ]);

        foreach ($request->sale_item_id as $index => $item_id) {
            $tax = 0;
            if ($request->is_taxable[$index] && !empty($request->tax_rate[$index])) {
                $tax = ($request->total_price[$index] * $request->tax_rate[$index]) / 100;
            }

            DailySale::create([
                'sale_item_id' => $item_id,
                'quantity' => $request->quantity[$index],
                'total_price' => $request->total_price[$index],
                'is_taxable' => $request->is_taxable[$index],
                'tax_rate' => $request->tax_rate[$index] ?? 0,
                'total_tax' => $tax,
                'sale_date' => $request->sale_date,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'sales added successfully']);
    }

    // عرض تفاصيل بيع يومي معين
    public function show($id)
    {
        $dailySale = DailySale::findOrFail($id);
        return view('dashboard.daily_sales.show', compact('dailySale'));
    }

    // عرض صفحة تعديل بيع يومي
    public function edit($id)
    {
        $dailySale = DailySale::findOrFail($id);
        $saleItems = SaleItem::all();
        return view('dashboard.daily_sales.edit', compact('dailySale', 'saleItems'));
    }

    // تحديث بيع يومي
    public function update(Request $request, $id)
    {
        $request->validate([
            'sale_item_id' => 'required|exists:sale_items,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'is_taxable' => 'nullable|boolean',
            'tax_rate' => 'nullable|numeric',
            'total_tax' => 'nullable|numeric',
            'sale_date' => 'required|date',
        ]);

        $dailySale = DailySale::findOrFail($id);

        $dailySale->update([
            'sale_item_id' => $request->sale_item_id,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'is_taxable' => $request->is_taxable,
            'tax_rate' => $request->tax_rate,
            'total_tax' => $request->total_tax,
            'sale_date' => $request->sale_date,
        ]);

        return redirect()->route('dashboard.daily_sales.index')
            ->with('success', 'Daily sale updated successfully.');

    }

    // حذف بيع يومي
    public function destroy($id)
    {
        $dailySale = DailySale::findOrFail($id);
        $dailySale->delete();

        return redirect()->route('dashboard.daily_sales.index')
            ->with('success', 'Daily sale deleted successfully.');
    }
}
