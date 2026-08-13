@extends('layouts.admin')

@section('page_title', isset($post) ? '编辑文章' : '发布文章')

@section('content')
<form action="{{ isset($post) ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
      method="POST" class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
    @csrf
    @if(isset($post)) @method('PUT') @endif

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
            <label class="block text-sm font-medium text-gray-700 mb-1">封面图片 URL</label>
            <div class="flex gap-4 items-start">
                <input type="text" name="cover" id="cover" value="{{ old('cover', $post->cover ?? '') }}" placeholder="https://..." class="flex-1 rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <img id="cover-preview" src="{{ old('cover', $post->cover ?? '') }}" alt="预览"
                     class="hidden w-20 h-20 object-cover rounded-lg border border-gray-200">
            </div>
            <p class="text-xs text-gray-400 mt-1">留空则前台自动展示默认占位图。粘贴图片链接后会即时预览。</p>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">摘要</label>
            <textarea name="summary" rows="2" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">{{ old('summary', $post->summary ?? '') }}</textarea>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">正文内容（支持 HTML）</label>
            <textarea name="content" rows="12" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-500 outline-none">{{ old('content', $post->content ?? '') }}</textarea>
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

    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('admin.posts.index') }}" class="px-5 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100">取消</a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium">保存</button>
    </div>
</form>

<script>
    (function () {
        var input = document.getElementById('cover');
        var img = document.getElementById('cover-preview');
        if (!input || !img) return;
        function sync() {
            var v = input.value.trim();
            if (v) { img.src = v; img.classList.remove('hidden'); }
            else { img.classList.add('hidden'); }
        }
        input.addEventListener('input', sync);
        img.addEventListener('error', function () { img.classList.add('hidden'); });
        sync();
    })();
</script>
