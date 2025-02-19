<x-app-layout>
    <div class="container mx-auto p-6">

        <h1 class="text-2xl font-bold mb-4">
            @lang('site.edit') @lang('site.purchaseitems')
        </h1>

        @if (session()->has('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('dashboard.purchase_items.update', $item->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.name')</label>
                <input type="text" name="name" value="{{ old('name', $item->name) }}" class="w-full border border-gray-300 rounded p-2">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.price')</label>
                <input type="number" name="price" value="{{ old('price', $item->price) }}" class="w-full border border-gray-300 rounded p-2" step="0.01">
                @error('price')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Purchase Category -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.purchasecategories')</label>
                <select name="purchase_category_id" class="w-full border border-gray-300 rounded p-2">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $item->purchase_category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('purchase_category_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded shadow hover:bg-green-700">
                @lang('site.update')
            </button>
        </form>

    </div>
</x-app-layout>
