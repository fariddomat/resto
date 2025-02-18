<div>
    <x-table
        :columns="['title', 'slug', 'created_at']"
        :data="$blogs"
        :edit="true"
        :delete="true"
        :routePrefix="'dashboard.blogs'"
    />

    <div class="mt-4">
        {{ $blogs->links() }}
    </div>
</div>
