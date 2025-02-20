<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-semibold text-grey-600 mb-6">@lang('site.dashboard')</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- إجمالي المشتريات --}}
            <a href="{{ route('dashboard.daily_purchases.index') }}" class="bg-blue-800 text-white p-6 rounded-lg shadow-lg transition hover:bg-blue-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">@lang('site.total_purchases')</h3>
                        <p class="text-2xl mt-2">{{ number_format($totalPurchases, 2) }} $</p>
                    </div>
                    <i class="fas fa-shopping-cart text-3xl"></i>
                </div>
            </a>

            {{-- عدد عمليات الشراء --}}
            <a href="{{ route('dashboard.daily_purchases.index') }}" class="bg-green-800 text-white p-6 rounded-lg shadow-lg transition hover:bg-green-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">@lang('site.purchase_count')</h3>
                        <p class="text-2xl mt-2">{{ $purchaseCount }}</p>
                    </div>
                    <i class="fas fa-file-invoice-dollar text-3xl"></i>
                </div>
            </a>

            {{-- إجمالي المبيعات --}}
            <a href="{{ route('dashboard.daily_sales.index') }}" class="bg-purple-800 text-white p-6 rounded-lg shadow-lg transition hover:bg-purple-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">@lang('site.total_sales')</h3>
                        <p class="text-2xl mt-2">{{ number_format($totalSales, 2) }} $</p>
                    </div>
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </a>

            {{-- عدد عمليات البيع --}}
            <a href="{{ route('dashboard.daily_sales.index') }}" class="bg-red-800 text-white p-6 rounded-lg shadow-lg transition hover:bg-red-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">@lang('site.sales_count')</h3>
                        <p class="text-2xl mt-2">{{ $salesCount }}</p>
                    </div>
                    <i class="fas fa-file-invoice text-3xl"></i>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
