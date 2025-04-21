<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DailyPurchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyPurchaseController extends Controller
{
    // عرض جميع المشتريات اليومية
    public function index()
    {
        $query = DailyPurchase::with('purchaseItem');

        // Default to today's date if no filters are applied
        if (!request('date') && !request('start_date') && !request('end_date') && !request('category_id')) {
            $query->whereDate('purchase_date', today());
        } else {
            // Apply existing scope filters
            $query->byDate(request('date'))
                  ->byDateRange(request('start_date'), request('end_date'))
                  ->byCategory(request('category_id'));
        }

        $dailyPurchases = $query->paginate(20)->appends(request()->query());

        return view('dashboard.daily_purchases.index', compact('dailyPurchases'));
    }

    // Confirm purchases (modified to handle any date for superadministrator)
    public function confirmPurchases(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadministrator'); // Adjust based on your role-checking method

        if ($isSuperAdmin) {
            // Superadmin can confirm purchases for any date
            $query = DailyPurchase::where('status', 'pending');
            if ($request->has('date')) {
                $query->whereDate('purchase_date', $request->date);
            } elseif ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
            }
            $query->update(['status' => 'confirmed']);
        } else {
            // Non-superadmin can only confirm today's purchases
            DailyPurchase::whereDate('purchase_date', today())
                ->where('status', 'pending')
                ->update(['status' => 'confirmed']);
        }

        return redirect()->route('dashboard.daily_purchases.index')
            ->with('success', 'Purchases confirmed successfully.');
    }

    // عرض صفحة إضافة شراء يومي جديد
    public function create()
    {
        $purchaseItems = PurchaseItem::all();
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
                'status' => 'pending',
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
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadministrator'); // Adjust based on your role-checking method

        // Allow superadmin to edit regardless of status
        if (!$isSuperAdmin && $dailyPurchase->status === 'confirmed') {
            return redirect()->route('dashboard.daily_purchases.index')
                ->with('error', 'Cannot edit confirmed purchases.');
        }

        $purchaseItems = PurchaseItem::all();
        return view('dashboard.daily_purchases.edit', compact('dailyPurchase', 'purchaseItems'));
    }

    // تحديث الشراء اليومي
    public function update(Request $request, $id)
    {
        $dailyPurchase = DailyPurchase::findOrFail($id);
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadministrator'); // Adjust based on your role-checking method

        // Allow superadmin to update regardless of status
        if (!$isSuperAdmin && $dailyPurchase->status === 'confirmed') {
            return redirect()->route('dashboard.daily_purchases.index')
                ->with('error', 'Cannot update confirmed purchases.');
        }

        $request->validate([
            'purchase_item_id' => 'required|exists:purchase_items,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'is_taxable' => 'nullable|boolean',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'total_tax' => 'nullable|numeric|min:0',
        ]);

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
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadministrator'); // Adjust based on your role-checking method

        // Allow superadmin to delete regardless of status
        if (!$isSuperAdmin && $dailyPurchase->status === 'confirmed') {
            return redirect()->route('dashboard.daily_purchases.index')
                ->with('error', 'Cannot delete confirmed purchases.');
        }

        $dailyPurchase->delete();

        return redirect()->route('dashboard.daily_purchases.index')
            ->with('success', 'Daily purchase deleted successfully.');
    }
}
