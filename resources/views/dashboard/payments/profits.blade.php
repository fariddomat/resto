<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-xl font-bold mb-4">@lang('site.profits_reports')</h2>

        <div class="overflow-hidden bg-white shadow-md rounded-lg">
            @foreach ($profits as $year => $yearProfits)
                <div class="border-b">
                    <div class="bg-purple-800 text-white px-4 py-2 cursor-pointer hover:bg-purple-900 flex justify-between items-center"
                         onclick="toggleSection('year-{{ $year }}')">
                        <span>{{ $year }}</span>
                        <span class="toggle-icon">▼</span>
                    </div>

                    <div id="year-{{ $year }}" class="hidden">
                        <table class="w-full border-collapse">
                            <thead class="bg-purple-100">
                                <tr>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.month')</th>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.revenues')</th>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.expenses')</th>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.profit')</th>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.profit_base')</th>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.profit_tax')</th>
                                    {{-- <th class="px-4 py-2 border border-gray-300">@lang('site.net_profit')</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($yearProfits as $profit)
                                    <tr class="border-b">
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ date('F', mktime(0, 0, 0, $profit->month, 1)) }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($profit->total_revenue, 2) }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($profit->total_expenses, 2) }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($profit->profit, 2) }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($profit->profit_base, 2) }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($profit->profit_tax, 2) }}
                                        </td>
                                        {{-- <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($profit->profit_base - $profit->profit_tax, 2) }}
                                        </td> --}}
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
