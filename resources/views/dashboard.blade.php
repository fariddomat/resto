<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-semibold text-grey-600 mb-6">{{ now()->format('F Y') }}</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-center">
            {{-- إجمالي المشتريات --}}
            <a href="{{ route('dashboard.daily_purchases.index') }}" class="bg-blue-800 text-white p-6 rounded-lg shadow-lg transition hover:bg-blue-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">@lang('site.total_purchases')</h3>
                        <p class="text-2xl mt-2">{{ number_format($totalPurchases, 2) }}</p>
                    </div>
                    <i class="fas fa-shopping-cart text-3xl"></i>
                </div>
            </a>

            {{-- إجمالي المبيعات --}}
            <a href="{{ route('dashboard.daily_sales.index') }}" class="bg-purple-800 text-white p-6 rounded-lg shadow-lg transition hover:bg-purple-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">@lang('site.total_sales')</h3>
                        <p class="text-2xl mt-2">{{ number_format($totalSales, 2) }}</p>
                    </div>
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </a>

            <a href="{{ route('dashboard.profits') }}" class="bg-green-800 text-white p-6 rounded-lg shadow-lg transition hover:bg-green-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">@lang('site.profits')</h3>
                        <p class="text-2xl mt-2">{{ number_format($totalProfits, 2) }}</p>
                    </div>
                    <i class="fas fa-file-invoice-dollar text-3xl"></i>
                </div>
            </a>
        </div>

        <!-- Bar Chart Section -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold text-grey-600 mb-4">@lang('site.yearly_overview') - {{ now()->year }}</h3>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <canvas id="yearlyChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('yearlyChart').getContext('2d');
            const monthlyData = @json($monthlyData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlyData.map(data => data.month),
                    datasets: [
                        {
                            label: '@lang("site.purchase")',
                            data: monthlyData.map(data => data.purchases),
                            backgroundColor: 'rgba(30, 64, 175, 0.5)', // Blue
                            borderColor: 'rgba(30, 64, 175, 1)',
                            borderWidth: 1
                        },
                        {
                            label: '@lang("site.sales")',
                            data: monthlyData.map(data => data.sales),
                            backgroundColor: 'rgba(147, 51, 234, 0.5)', // Purple
                            borderColor: 'rgba(147, 51, 234, 1)',
                            borderWidth: 1
                        },
                        {
                            label: '@lang("site.profits")',
                            data: monthlyData.map(data => data.profits),
                            backgroundColor: 'rgba(22, 163, 74, 0.5)', // Green
                            borderColor: 'rgba(22, 163, 74, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '@lang("site.total")'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '@lang("site.month")'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: '@lang("site.yearly_overview")'
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
