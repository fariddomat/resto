<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\BlogCategoryController;
use App\Http\Controllers\Dashboard\BlogController;
use App\Http\Controllers\Dashboard\DailyPurchaseController;
use App\Http\Controllers\Dashboard\DailySaleController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\PaymentController;
use App\Http\Controllers\Dashboard\PurchaseCategoryController;
use App\Http\Controllers\Dashboard\PurchaseItemController;
use App\Http\Controllers\Dashboard\SaleCategoryController;
use App\Http\Controllers\Dashboard\SaleItemController;
use App\Http\Controllers\Dashboard\UserController;
use App\Livewire\UserForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('/clear', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');

    return "Cleared!";
});

Route::middleware(['web'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');
});
Route::prefix('dashboard')
    ->name('dashboard.')
    ->middleware(['auth', 'role:superadministrator|blogger|admin'])
    ->group(
        function () {
            Route::resource('users', UserController::class);
            Route::resource('blogcategories', BlogCategoryController::class);
            Route::resource('blogs', BlogController::class);

            Route::resource('purchase_categories', PurchaseCategoryController::class);
            Route::resource('purchase_items', PurchaseItemController::class);
            Route::resource('daily_purchases', DailyPurchaseController::class);

            // Route::post('/daily-purchases/confirm', [DailyPurchaseController::class, 'confirmTodayPurchases'])
            // ->name('daily_purchases.confirm');

            Route::post('/daily-purchases/confirm', [DailyPurchaseController::class, 'confirmPurchases'])->name('daily_purchases.confirm');

            Route::resource('sale_categories', SaleCategoryController::class);
            Route::resource('sale_items', SaleItemController::class);
            Route::resource('daily_sales', DailySaleController::class);
            // Route::post('/daily-sales/confirm', [DailySaleController::class, 'confirmTodaySales'])
            // ->name('daily_sales.confirm');

            Route::post('/daily-sales/confirm', [DailySaleController::class, 'confirmSales'])->name('daily_sales.confirm');

            Route::get('expenses', [PaymentController::class, 'expenses'])->name('expenses');
            Route::get('revenues', [PaymentController::class, 'revenues'])->name('revenues');
            Route::get('profits', [PaymentController::class, 'profits'])->name('profits');
            Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
        }
    );
require __DIR__ . '/auth.php';
