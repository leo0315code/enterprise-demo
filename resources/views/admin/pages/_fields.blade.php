<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">页面标题</label>
        <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL 路径)</label>
        <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" required placeholder="about" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">SEO 标题</label>
        <input type="text" name="seo_title" value="{{ old('seo_title', $page->seo_title ?? '') }}" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">排序</label>
        <input type="number" name="sort" value="{{ old('sort', $page->sort ?? 0) }}" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">SEO 描述</label>
        <textarea name="seo_description" rows="2" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('seo_description', $page->seo_description ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">正文内容</label>
        <div class="rich-editor border border-gray-300 rounded-lg overflow-hidden">
            <div id="content-toolbar"></div>
            <div id="content-editor" style="min-height: 360px;"></div>
            <textarea name="content" data-rt-id="content" class="hidden">{{ old('content', $page->content ?? '') }}</textarea>
        </div>
        <p class="text-xs text-gray-400 mt-1">支持加粗、列表、图片上传等可视化排版，图片将上传至本站存储。</p>
    </div>
    <div class="md:col-span-2">
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }} class="rounded">
            在前台显示
        </label>
    </div>
</div>
