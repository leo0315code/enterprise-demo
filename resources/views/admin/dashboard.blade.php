@extends('layouts.admin')

@section('page_title', '仪表盘')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="text-sm text-gray-500">站点名称</div>
        <div class="text-2xl font-bold text-gray-800 mt-2">{{ setting('site_name', config('app.name')) }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="text-sm text-gray-500">联系电话</div>
        <div class="text-2xl font-bold text-gray-800 mt-2">{{ setting('contact_phone', '-') }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="text-sm text-gray-500">ICP 备案</div>
        <div class="text-lg font-semibold text-gray-800 mt-2">{{ setting('icp_number', '未填写') }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="text-sm text-gray-500">当前管理员</div>
        <div class="text-lg font-semibold text-gray-800 mt-2">{{ auth()->user()->name ?? '管理员' }}</div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="font-semibold text-gray-800 mb-4">快速开始</h2>
    <ul class="space-y-3 text-sm text-gray-600">
        <li class="flex items-center gap-2"><span class="text-blue-500">→</span> 在 <a href="{{ route('admin.settings.index') }}" class="text-blue-600 hover:underline">站点设置</a> 中完善企业名称、联系方式、Logo 等基础信息</li>
        <li class="flex items-center gap-2"><span class="text-blue-500">→</span> 在 <a href="{{ route('admin.pages.index') }}" class="text-blue-600 hover:underline">单页管理</a> 中编辑关于我们、联系方式等内容</li>
        <li class="flex items-center gap-2"><span class="text-blue-500">→</span> 在 <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">产品服务</a> 中添加企业产品或服务</li>
        <li class="flex items-center gap-2"><span class="text-blue-500">→</span> 在 <a href="{{ route('admin.posts.index') }}" class="text-blue-600 hover:underline">新闻文章</a> 中发布企业动态</li>
    </ul>
</div>
@endsection
