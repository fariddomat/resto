<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">@lang('site.sales')</h1>

        <!-- Add Sales Button -->
        <div class="flex gap-4 mb-4">
            <a href="{{ route('dashboard.daily_sales.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded shadow" wire:navigate>
                ➕ @lang('site.add') @lang('site.sales')
            </a>

            <!-- Confirm Today’s Sales Button -->
            <form action="{{ route('dashboard.daily_sales.confirm') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded shadow" onclick="return confirm(@lang('site.confirm_today_sales')?)">
                    ✅ @lang('site.confirm_today_sales')
                </button>
            </form>
        </div>

        <!-- Filter Form -->
        <form method="GET" class="mt-4 bg-white p-4 rounded shadow-md flex flex-wrap gap-4" id="filterForm">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">@lang('site.select_date')</label>
                <input type="date" id="date" name="date"
                       value="{{ request('start_date') || request('end_date') ? '' : request('date') }}"
                       class="border p-2 rounded w-full">
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">@lang('site.start_date')</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="border p-2 rounded w-full">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">@lang('site.end_date')</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="border p-2 rounded w-full">
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">@lang('site.category')</label>
                <select id="category_id" name="category_id" class="border p-[0.7rem] rounded w-full">
                    <option value="">@lang('site.all_categories')</option>
                    @foreach(\App\Models\SaleCategory::all() as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded shadow mt-6">
                    🔍 @lang('site.filter')
                </button>
            </div>
        </form>

        <!-- Sales Table -->
        <div class="overflow-x-auto mt-4">
            <x-table
                :columns="['saleItem.name', 'quantity', 'total_price', 'sale_date', 'status']"
                :data="$dailySales"
                :edit="(request('date') === null && request('start_date') === null && request('end_date') === null) || request('date') === today()->toDateString()"
                :delete="(request('date') === null && request('start_date') === null && request('end_date') === null) || request('date') === today()->toDateString()"
                :routePrefix="'dashboard.daily_sales'"
            />

            <!-- Pagination -->
            <div class="mt-4">
                {{ $dailySales->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const dateInput = document.getElementById('date');

            // Clear select_date when start_date changes
            startDateInput.addEventListener('change', function () {
                dateInput.value = '';
            });

            // Clear select_date when end_date changes
            endDateInput.addEventListener('change', function () {
                dateInput.value = '';
            });
        });
    </script>
</x-app-layout>
