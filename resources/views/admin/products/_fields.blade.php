<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">产品名称</label>
        <input type="text" name="title" value="{{ old('title', $product->title ?? '') }}" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">分类</label>
        <select name="category_id" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">未分类</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
        <input type="text" name="slug" value="{{ old('slug', $product->slug ?? '') }}" placeholder="留空自动生成" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">状态</label>
        <select name="status" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="active" {{ old('status', $product->status ?? 'active') == 'active' ? 'selected' : '' }}>上架</option>
            <option value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>下架</option>
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">封面图片 URL</label>
        <div class="flex gap-4 items-start">
            <input type="text" name="thumbnail" id="thumbnail" value="{{ old('thumbnail', $product->thumbnail ?? '') }}" placeholder="https://..." class="flex-1 rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <img id="thumbnail-preview" src="{{ old('thumbnail', $product->thumbnail ?? '') }}" alt="预览"
                 class="hidden w-20 h-20 object-cover rounded-lg border border-gray-200">
        </div>
        <p class="text-xs text-gray-400 mt-1">留空则前台自动展示默认占位图。粘贴图片链接后会即时预览。</p>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">简介</label>
        <textarea name="summary" rows="2" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">{{ old('summary', $product->summary ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">详细介绍（支持 HTML）</label>
        <textarea name="content" rows="10" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-500 outline-none">{{ old('content', $product->content ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">排序</label>
        <input type="number" name="sort" value="{{ old('sort', $product->sort ?? 0) }}" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <div class="flex items-end">
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }} class="rounded">
            设为首页推荐
        </label>
    </div>
</div>
