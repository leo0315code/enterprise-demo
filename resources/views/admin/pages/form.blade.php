@extends('layouts.admin')

@section('page_title', isset($page) ? '编辑页面' : '新增页面')

@section('content')
<form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
      method="POST" class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
    @csrf
    @if(isset($page)) @method('PUT') @endif

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
            <label class="block text-sm font-medium text-gray-700 mb-1">正文内容（支持 HTML）</label>
            <textarea name="content" rows="12" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('content', $page->content ?? '') }}</textarea>
        </div>
        <div class="md:col-span-2">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }} class="rounded">
                在前台显示
            </label>
        </div>
    </div>

    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('admin.pages.index') }}" class="px-5 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100">取消</a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium">保存</button>
    </div>
</form>
@endsection
