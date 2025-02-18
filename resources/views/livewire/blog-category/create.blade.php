<div>
    @if (session()->has('success'))
        <div class="bg-green-500 text-white p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="saveCategory"  class="bg-white p-6 rounded-lg shadow-md">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
            <input type="text" id="name" wire:model="name" class="w-full p-2 border border-gray-300 rounded shadow" placeholder="Enter category name">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Slug (optional) -->
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
            <input type="text" id="slug" wire:model="slug" class="w-full p-2 border border-gray-300 rounded shadow" placeholder="Enter slug (optional)">
            @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-700">
                @lang('site.create') Category
            </button>
        </div>
    </form>
</div>
