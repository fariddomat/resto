<div>

    <x-table
        :columns="['name', 'slug', 'created_at']"
        :data="$categories"
        :edit="true"
        :delete="true"
        :routePrefix="'dashboard.blogcategories'"
    />

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
