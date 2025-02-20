<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DailyPurchase;
use App\Models\DailySale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    function expenses()
    {
        $expenses = DailyPurchase::selectRaw('YEAR(purchase_date) as year, MONTH(purchase_date) as month, SUM(total_price) as total_expenses')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        return view('dashboard.payments.expenses', compact('expenses'));
    }

    public function revenues()
    {
        $revenues = DailySale::selectRaw('YEAR(sale_date) as year, MONTH(sale_date) as month, SUM(total_price) as total_revenue')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        return view('dashboard.payments.revenues', compact('revenues'));
    }

    public function profits()
    {
        $profits = DailySale::selectRaw('YEAR(sale_date) as year, MONTH(sale_date) as month, SUM(total_price) as total_revenue')
            ->joinSub(
                DailyPurchase::selectRaw('YEAR(purchase_date) as year, MONTH(purchase_date) as month, SUM(total_price) as total_expenses')
                    ->groupBy('year', 'month'),
                'expenses',
                function ($join) {
                    $join->on('expenses.year', '=', 'daily_sales.year')
                        ->on('expenses.month', '=', 'daily_sales.month');
                }
            )
            ->groupBy('daily_sales.year', 'daily_sales.month')
            ->orderByDesc('daily_sales.year')
            ->orderByDesc('daily_sales.month')
            ->selectRaw('(SUM(total_price) - expenses.total_expenses) as profit')
            ->get();

        return view('dashboard.payments.profits', compact('profits'));
    }
}
