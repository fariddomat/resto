<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">@lang('site.sales')</h1>

        <!-- Add Sales Button -->
        <a href="{{ route('dashboard.daily_sales.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded shadow" wire:navigate>
            ➕ @lang('site.add') @lang('site.sales')
        </a>

        <!-- Filter Form -->
        <form method="GET" class="mt-4 bg-white p-4 rounded shadow-md flex flex-wrap gap-4">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">@lang('site.select_date')</label>
                <input type="date" id="date" name="date" value="{{ request('date') }}" class="border p-2 rounded w-full">
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
                :columns="['saleItem.name', 'quantity', 'total_price', 'sale_date']"
                :data="$dailySales"
                :edit="true"
                :delete="true"
                :routePrefix="'dashboard.daily_sales'"
            />

            <!-- Pagination -->
            <div class="mt-4">
                {{ $dailySales->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
