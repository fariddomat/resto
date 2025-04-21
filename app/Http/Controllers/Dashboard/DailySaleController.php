<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DailySale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailySaleController extends Controller
{
    // عرض جميع المبيعات اليومية
    public function index()
    {
        $query = DailySale::with('saleItem');

        // Default to today's date if no filters are applied
        if (!request('date') && !request('start_date') && !request('end_date') && !request('category_id')) {
            $query->whereDate('sale_date', today());
        }

        // Filter by exact date
        if (request('date')) {
            $query->whereDate('sale_date', request('date'));
        }

        // Filter by date range (start & end date)
        if (request('start_date') && request('end_date')) {
            $query->whereBetween('sale_date', [request('start_date'), request('end_date')]);
        }

        // Filter by category
        if (request('category_id')) {
            $query->whereHas('saleItem.saleCategory', function ($q) {
                $q->where('id', request('category_id'));
            });
        }

        $dailySales = $query->paginate(20)->appends(request()->query());

        return view('dashboard.daily_sales.index', compact('dailySales'));
    }

    // Confirm sales (modified to handle any date for superadministrator)
    public function confirmSales(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadministrator'); // Adjust based on your role-checking method

        if ($isSuperAdmin) {
            // Superadmin can confirm sales for any date
            $query = DailySale::where('status', 'pending');
            if ($request->has('date')) {
                $query->whereDate('sale_date', $request->date);
            } elseif ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('sale_date', [$request->start_date, $request->end_date]);
            }
            $query->update(['status' => 'confirmed']);
        } else {
            // Non-superadmin can only confirm today's sales
            DailySale::whereDate('sale_date', today())
                ->where('status', 'pending')
                ->update(['status' => 'confirmed']);
        }

        return redirect()->route('dashboard.daily_sales.index')
            ->with('success', 'Sales confirmed successfully.');
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
                'status' => 'pending', // Ensure status is set
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Sales added successfully']);
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
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadministrator'); // Adjust based on your role-checking method

        // Allow superadmin to edit regardless of status
        if (!$isSuperAdmin && $dailySale->status === 'confirmed') {
            return redirect()->route('dashboard.daily_sales.index')
                ->with('error', 'Cannot edit confirmed sales.');
        }

        $saleItems = SaleItem::all();
        return view('dashboard.daily_sales.edit', compact('dailySale', 'saleItems'));
    }

    // تحديث بيع يومي
    public function update(Request $request, $id)
    {
        $dailySale = DailySale::findOrFail($id);
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadministrator'); // Adjust based on your role-checking method

        // Allow superadmin to update regardless of status
        if (!$isSuperAdmin && $dailySale->status === 'confirmed') {
            return redirect()->route('dashboard.daily_sales.index')
                ->with('error', 'Cannot update confirmed sales.');
        }

        $request->validate([
            'sale_item_id' => 'required|exists:sale_items,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'is_taxable' => 'nullable|boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'total_tax' => 'nullable|numeric|min:0',
            'sale_date' => 'required|date',
        ]);

        $tax = 0;
        if ($request->is_taxable && $request->tax_rate) {
            $tax = ($request->total_price * $request->tax_rate) / 100;
        }

        $dailySale->update([
            'sale_item_id' => $request->sale_item_id,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'is_taxable' => $request->is_taxable,
            'tax_rate' => $request->tax_rate,
            'total_tax' => $tax,
            'sale_date' => $request->sale_date,
        ]);

        return redirect()->route('dashboard.daily_sales.index')
            ->with('success', 'Daily sale updated successfully.');
    }

    // حذف بيع يومي
    public function destroy($id)
    {
        $dailySale = DailySale::findOrFail($id);
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadministrator'); // Adjust based on your role-checking method

        // Allow superadmin to delete regardless of status
        if (!$isSuperAdmin && $dailySale->status === 'confirmed') {
            return redirect()->route('dashboard.daily_sales.index')
                ->with('error', 'Cannot delete confirmed sales.');
        }

        $dailySale->delete();

        return redirect()->route('dashboard.daily_sales.index')
            ->with('success', 'Daily sale deleted successfully.');
    }
}
