<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">文章标题</label>
        <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">分类</label>
        <select name="category_id" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">未分类</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $post->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">作者</label>
        <input type="text" name="author" value="{{ old('author', $post->author ?? '') }}" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL)</label>
        <input type="text" name="slug" value="{{ old('slug', $post->slug ?? '') }}" placeholder="留空自动生成" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">发布时间</label>
        <input type="date" name="published_at" value="{{ old('published_at', isset($post) ? ($post->published_at?->format('Y-m-d') ?? '') : date('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">封面图片</label>
        <div class="image-picker flex gap-4 items-start" style="--ip-accent:#3b82f6">
            <input type="text" name="cover" class="ip-url flex-1 rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" value="{{ old('cover', $post->cover ?? '') }}" placeholder="https://... 或点右侧按钮上传">
            <button type="button" class="ip-btn shrink-0 px-4 py-2 rounded-lg text-sm font-medium text-white" style="background:var(--ip-accent)">上传图片</button>
            <input type="file" accept="image/*" class="ip-file hidden">
            <img class="ip-preview hidden w-20 h-20 object-cover rounded-lg border border-gray-200" src="{{ old('cover', $post->cover ?? '') }}" alt="预览">
        </div>
        <p class="text-xs text-gray-400 mt-1">可贴外链或点"上传图片"直传本站（留空则前台展示默认占位图）。</p>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">摘要</label>
        <textarea name="summary" rows="2" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">{{ old('summary', $post->summary ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">正文内容</label>
        <div class="rich-editor border border-gray-300 rounded-lg overflow-hidden">
            <div id="content-toolbar"></div>
            <div id="content-editor" style="min-height: 320px;"></div>
            <textarea name="content" data-rt-id="content" class="hidden">{{ old('content', $post->content ?? '') }}</textarea>
        </div>
        <p class="text-xs text-gray-400 mt-1">支持加粗、列表、图片上传等可视化排版，图片将上传至本站存储。</p>
    </div>
    <div class="flex items-center gap-6">
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }} class="rounded"> 设为头条
        </label>
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $post->is_active ?? true) ? 'checked' : '' }} class="rounded"> 公开显示
        </label>
    </div>
</div>
