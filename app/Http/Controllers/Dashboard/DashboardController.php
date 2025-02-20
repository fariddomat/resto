<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DailyPurchase;
use App\Models\DailySale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalPurchases' => DailyPurchase::sum('total_price'),  // إجمالي تكلفة المشتريات
            'totalSales' => DailySale::sum('total_price'),          // إجمالي تكلفة المبيعات
            'purchaseCount' => DailyPurchase::count(),             // عدد عمليات الشراء
            'salesCount' => DailySale::count(),                    // عدد عمليات البيع
        ]);
    }
}
