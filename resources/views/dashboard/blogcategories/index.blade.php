<x-app-layout>


    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Categories</h1>
        <a href="{{ route('dashboard.blogcategories.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded shadow"
            wire:navigate>➕ @lang('site.add') Category</a>

        <div class="overflow-x-auto mt-4">
            <livewire:blog-category.index />
        </div>
    </div>
</x-app-layout>
