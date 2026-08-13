<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">分类名称</label>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
        <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}" placeholder="留空自动生成" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">排序</label>
        <input type="number" name="sort" value="{{ old('sort', $category->sort ?? 0) }}" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <div>
        <label class="flex items-center gap-2 text-sm text-gray-700 mt-6">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} class="rounded"> 启用
        </label>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">描述</label>
        <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $category->description ?? '') }}</textarea>
    </div>
</div>
