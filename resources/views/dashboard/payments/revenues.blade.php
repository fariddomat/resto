<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-xl font-bold mb-4">@lang('site.revenues_report')</h2>

        <div class="overflow-hidden bg-white shadow-md rounded-lg">

            <table class="w-full border-collapse">
                <!-- Table Head -->
                <thead class="bg-blue-800 text-white">           <tr>
                    <th class="px-4 py-2 border border-gray-300  ">@lang('site.year')</th>
                    <th class="px-4 py-2 border border-gray-300  ">@lang('site.month')</th>
                    <th class="px-4 py-2 border border-gray-300  ">@lang('site.total_revenue')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($revenues as $revenue)
                    <tr class="border-b">
                        <td class="px-4 py-2 border border-gray-300  ">{{ $revenue->year }}</td>
                        <td class="px-4 py-2 border border-gray-300  ">{{ date("F", mktime(0, 0, 0, $revenue->month, 1)) }}</td>
                        <td class="px-4 py-2 border border-gray-300  ">{{ number_format($revenue->total_revenue, 2) }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

</x-app-layout>
