<x-app-layout>
    <div class="container mx-auto p-6">

        <h1 class="text-2xl font-bold mb-4">
            @lang('site.edit') @lang('site.purchasecategories')
        </h1>

        @if (session()->has('message'))
            <div class="alert alert-success" id="success-message">
                {{ session('message') }}
            </div>
        @endif

        <form action="{{ route('dashboard.purchase_categories.update', $category->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @method('PUT')
            @csrf
            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">@lang('site.name')</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full border border-gray-300 rounded p-2">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Submit Button -->
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-700">
                @lang('site.update')
            </button>
        </form>
    </div>
    </x-app-layout>
