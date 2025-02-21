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
        // استعلام المبيعات مع التجميع حسب السنة والشهر
        $sales = DailySale::selectRaw('YEAR(sale_date) as year, MONTH(sale_date) as month, SUM(total_price) as total_revenue')
            ->groupByRaw('YEAR(sale_date), MONTH(sale_date)');

        // استعلام المصاريف مع التجميع حسب السنة والشهر
        $expenses = DailyPurchase::selectRaw('YEAR(purchase_date) as year, MONTH(purchase_date) as month, SUM(total_price) as total_expenses')
            ->groupByRaw('YEAR(purchase_date), MONTH(purchase_date)');

        // الانضمام إلى الاستعلامات الفرعية باستخدام fromSub()
        $profits = DB::query()
            ->fromSub($sales, 'sales')
            ->leftJoinSub($expenses, 'expenses', function ($join) {
                $join->on('sales.year', '=', 'expenses.year')
                    ->on('sales.month', '=', 'expenses.month');
            })
            ->selectRaw('sales.year, sales.month, total_revenue, COALESCE(total_expenses, 0) as total_expenses, (total_revenue - COALESCE(total_expenses, 0)) as profit')
            ->orderByDesc('sales.year')
            ->orderByDesc('sales.month')
            ->get();

        return view('dashboard.payments.profits', compact('profits'));
    }


}
