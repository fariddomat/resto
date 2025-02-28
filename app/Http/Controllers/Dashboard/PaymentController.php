<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DailyPurchase;
use App\Models\DailySale;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function expenses()
{
    $expenses = DailySale::selectRaw('
        YEAR(sale_date) as year,
        MONTH(sale_date) as month,
        SUM(
            CASE
                WHEN is_taxable THEN total_price / 1.15
                ELSE total_price
            END
        ) as total_base,
        SUM(
            CASE
                WHEN is_taxable THEN (total_price - (total_price / 1.15))
                ELSE 0
            END
        ) as total_tax
    ')
    ->groupBy('year', 'month')
    ->orderByDesc('year')
    ->orderByDesc('month')
    ->get()
    ->groupBy('year');

    return view('dashboard.payments.expenses', compact('expenses'));
}

public function revenues()
{
    $revenues = DailyPurchase::selectRaw('
        YEAR(purchase_date) as year,
        MONTH(purchase_date) as month,
        SUM(
            CASE
                WHEN is_taxable THEN total_price / 1.15
                ELSE total_price
            END
        ) as total_base,
        SUM(
            CASE
                WHEN is_taxable THEN (total_price - (total_price / 1.15))
                ELSE 0
            END
        ) as total_tax
    ')
    ->groupBy('year', 'month')
    ->orderByDesc('year')
    ->orderByDesc('month')
    ->get()
    ->groupBy('year');

    return view('dashboard.payments.revenues', compact('revenues'));
}


public function profits()
{
    // Sales query (raw total, no tax separation yet)
    $sales = DailySale::selectRaw('
        YEAR(sale_date) as year,
        MONTH(sale_date) as month,
        SUM(total_price) as total_revenue
    ')
    ->groupByRaw('YEAR(sale_date), MONTH(sale_date)');

    // Expenses query (raw total, no tax separation yet)
    $expenses = DailyPurchase::selectRaw('
        YEAR(purchase_date) as year,
        MONTH(purchase_date) as month,
        SUM(total_price) as total_expenses
    ')
    ->groupByRaw('YEAR(purchase_date), MONTH(purchase_date)');

    // Combined query with profit calculation and then tax on profit
    $profits = DB::query()
        ->fromSub($sales, 'sales')
        ->leftJoinSub($expenses, 'expenses', function ($join) {
            $join->on('sales.year', '=', 'expenses.year')
                 ->on('sales.month', '=', 'expenses.month');
        })
        ->selectRaw('
            sales.year,
            sales.month,
            total_revenue,
            COALESCE(total_expenses, 0) as total_expenses,
            (total_revenue - COALESCE(total_expenses, 0)) as profit,
            CASE
                WHEN (total_revenue - COALESCE(total_expenses, 0)) > 0
                THEN (total_revenue - COALESCE(total_expenses, 0)) / 1.15
                ELSE (total_revenue - COALESCE(total_expenses, 0))
            END as profit_base,
            CASE
                WHEN (total_revenue - COALESCE(total_expenses, 0)) > 0
                THEN ((total_revenue - COALESCE(total_expenses, 0)) - ((total_revenue - COALESCE(total_expenses, 0)) / 1.15))
                ELSE 0
            END as profit_tax
        ')
        ->orderByDesc('sales.year')
        ->orderByDesc('sales.month')
        ->get()
        ->groupBy('year'); // Group by year for collapsing

    return view('dashboard.payments.profits', compact('profits'));
}

}
