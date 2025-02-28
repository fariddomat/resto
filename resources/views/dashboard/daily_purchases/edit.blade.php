<x-app-layout>
    <div class="container mx-auto p-6">

        <h1 class="text-2xl font-bold mb-4">
            @lang('site.edit') @lang('site.purchase')
        </h1>

        @if (session()->has('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('dashboard.daily_purchases.update', $dailyPurchase->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @method('PUT')
            @csrf

            <!-- Select Purchase Item -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.purchase_item')</label>
                <select name="purchase_item_id" class="w-full border border-gray-300 rounded p-2">
                    <option value="">@lang('site.select_item')</option>
                    @foreach ($purchaseItems as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $dailyPurchase->purchase_item_id ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('purchase_item_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.quantity')</label>
                <input type="number" name="quantity" value="{{ old('quantity', $dailyPurchase->quantity) }}" class="w-full border border-gray-300 rounded p-2" min="1">
                @error('quantity')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Total Price -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.total_price')</label>
                <input type="number" name="total_price" value="{{ old('total_price', $dailyPurchase->total_price) }}" class="w-full border border-gray-300 rounded p-2" min="0">
                @error('total_price')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Purchase Date -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.purchase_date')</label>
                <input type="date" name="purchase_date" value="{{ old('purchase_date', $dailyPurchase->purchase_date) }}" class="w-full border border-gray-300 rounded p-2">
                @error('purchase_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Taxable -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.is_taxable')</label>
                <select name="is_taxable" class="w-full border border-gray-300 rounded p-2">
                    <option value="1" {{ $dailyPurchase->is_taxable ? 'selected' : '' }}>@lang('site.yes')</option>
                    <option value="0" {{ !$dailyPurchase->is_taxable ? 'selected' : '' }}>@lang('site.no')</option>
                </select>
                @error('is_taxable')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tax Rate -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.note')</label>
                <input type="text" name="note" value="{{ old('note', $dailyPurchase->note) }}" class="w-full border border-gray-300 rounded p-2" min="0" max="100">
                @error('note')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Total Tax -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.total_tax')</label>
                <input type="number" name="total_tax" value="{{ old('total_tax', $dailyPurchase->total_tax) }}" class="w-full border border-gray-300 rounded p-2" min="0" readonly>
                @error('total_tax')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('dashboard.daily_purchases.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">@lang('site.cancel')</a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">@lang('site.update')</button>
            </div>
        </form>
    </div>
</x-app-layout>

@section('scripts')
<script>
    // حساب إجمالي الضريبة
    document.getElementById('total_price').addEventListener('input', function () {
        calculateTotalTax();
    });
    document.getElementById('tax_rate').addEventListener('input', function () {
        calculateTotalTax();
    });

    function calculateTotalTax() {
        const totalPrice = parseFloat(document.getElementById('total_price').value) || 0;
        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const totalTax = (totalPrice * taxRate) / 100;
        document.getElementById('total_tax').value = totalTax.toFixed(2);
    }
</script>
@endsection
