<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-xl font-bold mb-4">@lang('site.revenues_report')</h2>

        <div class="overflow-hidden bg-white shadow-md rounded-lg">
            @foreach ($revenues as $year => $yearRevenues)
                <div class="border-b">
                    <div class="bg-green-800 text-white px-4 py-2 cursor-pointer hover:bg-green-900 flex justify-between items-center"
                         onclick="toggleSection('year-{{ $year }}')">
                        <span>{{ $year }}</span>
                        <span class="toggle-icon">▼</span>
                    </div>

                    <div id="year-{{ $year }}" class="hidden">
                        <table class="w-full border-collapse">
                            <thead class="bg-green-100">
                                <tr>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.month')</th>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.base_total')</th>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.tax')</th>
                                    <th class="px-4 py-2 border border-gray-300">@lang('site.total_with_tax')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($yearRevenues as $revenue)
                                    <tr class="border-b">
                                        <td class="px-4 py-2 border border-gray-300">
                                            <a href="{{ route('dashboard.daily_purchases.index', [
                                                'start_date' => date('Y-m-01', mktime(0, 0, 0, $revenue->month, 1, $year)),
                                                'end_date' => date('Y-m-t', mktime(0, 0, 0, $revenue->month, 1, $year))
                                            ]) }}"
                                               class="text-blue-600 hover:underline">
                                                {{ date('F', mktime(0, 0, 0, $revenue->month, 1)) }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($revenue->total_base, 2) }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($revenue->total_tax, 2) }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($revenue->total_base + $revenue->total_tax, 2) }}
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
