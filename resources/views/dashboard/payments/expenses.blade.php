<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-xl font-bold mb-4">@lang('site.expenses_report')</h2>

        <div class="overflow-hidden bg-white shadow-md rounded-lg">
            @foreach ($expenses as $year => $yearExpenses)
                <div class="border-b">
                    <div class="bg-blue-800 text-white px-4 py-2 cursor-pointer hover:bg-blue-900 flex justify-between items-center"
                         onclick="toggleSection('year-{{ $year }}')">
                        <span>{{ $year }}</span>
                        <span class="toggle-icon">▼</span>
                    </div>

                    <div id="year-{{ $year }}" class="hidden">
                        <table class="w-full border-collapse">
                            <thead class="bg-blue-100">
                                <tr>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.month')</th>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.base_total')</th>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.tax')</th>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.total_with_tax')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($yearExpenses as $expense)
                                    <tr class="border-b">
                                        <td class="px-4 py-2 border border-gray-300">
                                            <a href="{{ route('dashboard.daily_sales.index', [
                                                'start_date' => date('Y-m-01', mktime(0, 0, 0, $expense->month, 1, $year)),
                                                'end_date' => date('Y-m-t', mktime(0, 0, 0, $expense->month, 1, $year))
                                            ]) }}"
                                               class="text-blue-600 hover:underline">
                                                {{ date('F', mktime(0, 0, 0, $expense->month, 1)) }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($expense->total_base, 2) }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($expense->total_tax, 2) }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($expense->total_base + $expense->total_tax, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function toggleSection(yearId) {
            const section = document.getElementById(yearId);
            const icon = event.currentTarget.querySelector('.toggle-icon');
            section.classList.toggle('hidden');
            icon.textContent = section.classList.contains('hidden') ? '▼' : '▲';
        }
    </script>
</x-app-layout>
