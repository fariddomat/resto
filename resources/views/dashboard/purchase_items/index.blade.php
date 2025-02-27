<x-app-layout>


    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">@lang('site.purchaseitems')</h1>
        <a href="{{ route('dashboard.purchase_items.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded shadow"  wire:navigate>➕ @lang('site.add') @lang('site.purchaseitems')</a>

        <div class="overflow-x-auto mt-4">
            <div>
                <x-table
        :columns="['name','purchaseCategory.name' ,'price']"
        :data="$items"
        :edit="true"
        :delete="true"
        :routePrefix="'dashboard.purchase_items'"
    />

    <div class="mt-4">
        {{ $items->links() }}
    </div>
            </div>
        </div>
    </div>
    </x-app-layout>
