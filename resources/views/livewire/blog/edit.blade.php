<div>
    <form wire:submit.prevent="update"  class="bg-white p-6 rounded-lg shadow-md">
        <div class="p-4">
            <div>
                <label for="title">Title</label>
                <input type="text" wire:model="title" class="w-full p-2 border border-gray-300 rounded" />
            </div>

            <div>
                <label for="slug">Slug</label>
                <input type="text" wire:model="slug" class="w-full p-2 border border-gray-300 rounded" />
            </div>

            <div>
                <label for="category">Category</label>
                <select wire:model="category" class="w-full p-2 border border-gray-300 rounded">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="image">Main Image</label>
                <input type="file" wire:model="image" class="w-full p-2 border border-gray-300 rounded" />
                @if ($blog->image)
                    <img src="{{ asset($blog->image) }}" class="mt-2 w-32" />
                @endif
            </div>

            <div>
                <label for="author_image">Author Image</label>
                <input type="file" wire:model="author_image" class="w-full p-2 border border-gray-300 rounded" />
                @if ($blog->author_image)
                    <img src="{{ asset($blog->author_image) }}" class="mt-2 w-32" />
                @endif
            </div>

            <div>
                <label for="image_alt">Image Alt Text</label>
                <input type="text" wire:model="image_alt" class="w-full p-2 border border-gray-300 rounded" />
            </div>

            <div>
                <label for="index_image_alt">Index Image Alt Text</label>
                <input type="text" wire:model="index_image_alt" class="w-full p-2 border border-gray-300 rounded" />
            </div>

            <div>
                <label for="author_name">Author Name</label>
                <input type="text" wire:model="author_name" class="w-full p-2 border border-gray-300 rounded" />
            </div>

            <div>
                <label for="author_title">Author Title</label>
                <input type="text" wire:model="author_title" class="w-full p-2 border border-gray-300 rounded" />
            </div>

            <div>
                <label for="description">Description</label>
                <textarea wire:model="description" class="w-full p-2 border border-gray-300 rounded"></textarea>
            </div>

            <div>
                <label for="introduction">Introduction</label>
                <textarea wire:model="introduction" class="w-full p-2 border border-gray-300 rounded"></textarea>
            </div>

            <div>
                <label for="content_table">Content Table</label>
                <textarea wire:model="content_table" class="w-full p-2 border border-gray-300 rounded"></textarea>
            </div>

            <div>
                <label for="first_paragraph">First Paragraph</label>
                <textarea wire:model="first_paragraph" class="w-full p-2 border border-gray-300 rounded"></textarea>
            </div>

            <div>
                <label for="show_at_home">Show at Home</label>
                <input type="checkbox" wire:model="show_at_home" class="mr-2" />
            </div>

            <div>
                <label for="showed">Showed</label>
                <input type="checkbox" wire:model="showed" class="mr-2" />
            </div>

            <button type="submit" class="bg-blue-500 text-white rounded p-2">@lang('site.update') @lang('site.blog')</button>
        </div>
    </form>
</div>
