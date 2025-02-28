<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">@lang('site.purchase')</h1>

        <a href="{{ route('dashboard.daily_purchases.create') }}"
            class="px-4 py-2 bg-blue-500 text-white rounded shadow"
            wire:navigate>
            ➕ @lang('site.add') @lang('site.purchase')
        </a>

        <!-- Filters Section -->
        <form method="GET" class="mt-4 bg-white p-4 rounded shadow-md flex flex-wrap gap-4">
            <!-- Date Filter -->
            <div>
                <label class="block text-sm font-medium">@lang('site.select_date')</label>
                <input type="date" name="date" value="{{ request('date') }}" class="border p-2 rounded w-full">
            </div>

            <!-- Date Range Filter -->
            <div>
                <label class="block text-sm font-medium">@lang('site.start_date')</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border p-2 rounded w-full">
            </div>

            <div>
                <label class="block text-sm font-medium">@lang('site.end_date')</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border p-2 rounded w-full">
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">@lang('site.category')</label>
                <select name="category_id" class="border p-[0.7rem] rounded w-full">
                    <option value="">@lang('site.all')</option>
                    @foreach(\App\Models\PurchaseCategory::all() as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded shadow mt-6">
                    🔍 @lang('site.filter')
                </button>
            </div>
        </form>

        <!-- Table Section -->
        <div class="overflow-x-auto mt-4">
            <x-table
                :columns="['purchaseItem.name', 'quantity', 'total_price', 'purchase_date']"
                :data="$dailyPurchases"
                :edit="true"
                :delete="true"
                :routePrefix="'dashboard.daily_purchases'"
            />

            <div class="mt-4">
                {{ $dailyPurchases->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
