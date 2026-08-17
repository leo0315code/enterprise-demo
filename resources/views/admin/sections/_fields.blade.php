<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">板块类型</label>
        <select name="type" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            @foreach(['hero'=>'Hero Banner','intro'=>'公司简介','features'=>'核心优势（卡片）','products'=>'推荐产品','news'=>'最新新闻','cta'=>'行动召唤','custom'=>'自定义内容'] as $val=>$label)
                <option value="{{ $val }}" {{ old('type', $section->type ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">排序</label>
        <input type="number" name="sort" value="{{ old('sort', $section->sort ?? 0) }}" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">标题</label>
        <input type="text" name="title" value="{{ old('title', $section->title ?? '') }}" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">副标题</label>
        <input type="text" name="subtitle" value="{{ old('subtitle', $section->subtitle ?? '') }}" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">按钮文字</label>
        <input type="text" name="button_text" value="{{ old('button_text', $section->button_text ?? '') }}" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">按钮链接</label>
        <input type="text" name="button_link" value="{{ old('button_link', $section->button_link ?? '') }}" placeholder="/about" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">图片 / 背景 URL</label>
        <input type="text" name="image" value="{{ old('image', $section->image ?? '') }}" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">正文内容（支持 HTML）</label>
        <textarea name="content" rows="6" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono">{{ old('content', $section->content ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">扩展配置（JSON，如卡片列表）</label>
        <textarea name="extra" rows="4" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono">{{ old('extra', isset($section) && $section->extra ? json_encode($section->extra, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : '') }}</textarea>
        <p class="text-xs text-gray-400 mt-1">格式：[{"icon":"🚀","title":"标题","desc":"描述"}]</p>
    </div>

    <div class="md:col-span-2">
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $section->is_active ?? true) ? 'checked' : '' }} class="rounded">
            在前台显示该板块
        </label>
    </div>
</div>
