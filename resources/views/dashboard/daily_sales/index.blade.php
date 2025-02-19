<x-app-layout>


    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">@lang('site.sales')</h1>
        <a href="{{ route('dashboard.daily_sales.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded shadow"  wire:navigate>➕ @lang('site.add') @lang('site.sales')</a>

        <div class="overflow-x-auto mt-4">
            <div>
                <x-table
        :columns="['name', 'created_at']"
        :data="$dailySales"
        :edit="true"
        :delete="true"
        :routePrefix="'dashboard.daily_sales'"
    />

    <div class="mt-4">
        {{ $dailySales->links() }}
    </div>
            </div>
        </div>
    </div>
    </x-app-layout>
