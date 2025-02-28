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
        // Get current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Dashboard stats for current month
        $totalPurchases = DailyPurchase::whereMonth('purchase_date', $currentMonth)
            ->whereYear('purchase_date', $currentYear)
            ->sum('total_price');

        $totalSales = DailySale::whereMonth('sale_date', $currentMonth)
            ->whereYear('sale_date', $currentYear)
            ->sum('total_price');

        $purchaseCount = DailyPurchase::whereMonth('purchase_date', $currentMonth)
            ->whereYear('purchase_date', $currentYear)
            ->count();

        $salesCount = DailySale::whereMonth('sale_date', $currentMonth)
            ->whereYear('sale_date', $currentYear)
            ->count();

        $totalProfits = $totalSales - $totalPurchases;

        // Yearly data for chart
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $purchases = DailyPurchase::whereMonth('purchase_date', $month)
                ->whereYear('purchase_date', $currentYear)
                ->sum('total_price');

            $sales = DailySale::whereMonth('sale_date', $month)
                ->whereYear('sale_date', $currentYear)
                ->sum('total_price');

            $monthlyData[] = [
                'month' => date('F', mktime(0, 0, 0, $month, 1)),
                'purchases' => $purchases,
                'sales' => $sales,
                'profits' => $sales - $purchases,
            ];
        }

        return view('dashboard', compact(
            'totalPurchases',
            'totalSales',
            'purchaseCount',
            'salesCount',
            'totalProfits',
            'monthlyData'
        ));
    }
}
