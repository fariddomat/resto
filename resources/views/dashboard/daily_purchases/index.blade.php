<x-app-layout>


    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">@lang('site.purchase')</h1>
        <a href="{{ route('dashboard.daily_purchases.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded shadow"  wire:navigate>➕ @lang('site.add') @lang('site.purchase')</a>

        <div class="overflow-x-auto mt-4">
            <div>
                <x-table
        :columns="['name', 'created_at']"
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
    </div>
    </x-app-layout>
