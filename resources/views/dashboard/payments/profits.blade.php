<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-xl font-bold mb-4">@lang('site.profits_reports')</h2>

        <div class="overflow-hidden bg-white shadow-md rounded-lg">

            <table class="w-full border-collapse">
                <!-- Table Head -->
                <thead class="bg-blue-800 text-white">
                <tr>
                    <th class="px-4 py-2 border border-gray-300">@lang('site.year')</th>
                    <th class="px-4 py-2 border border-gray-300">@lang('site.month')</th>
                    <th class="px-4 py-2 border border-gray-300">@lang('site.total_revenue')</th>
                    <th class="px-4 py-2 border border-gray-300">@lang('site.total_expenses')</th>
                    <th class="px-4 py-2 border border-gray-300">@lang('site.profit')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($profits as $profit)
                    <tr class="border-b">
                        <td class="px-4 py-2 border border-gray-300">{{ $profit->year }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ date("F", mktime(0, 0, 0, $profit->month, 1)) }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ number_format($profit->total_revenue, 2) }}</td>
                        <td class="px-4 py-2 border border-gray-300">{{ number_format($profit->total_expenses, 2) }}</td>
                        <td class="px-4 py-2 border border-gray-300 font-bold
                            {{ $profit->profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($profit->profit, 2) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
</x-app-layout>
