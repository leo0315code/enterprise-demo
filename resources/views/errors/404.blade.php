@extends('layouts.app')

@section('title', '页面不存在')

@section('content')
<section class="py-28 bg-gray-bg">
    <div class="max-w-xl mx-auto px-4 text-center">
        <div class="text-7xl font-bold text-primary mb-4">404</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">抱歉，页面走丢了</h1>
        <p class="text-gray-500 mb-8">您访问的页面不存在或已被移除，请检查网址是否正确。</p>
        <div class="flex justify-center gap-4">
            <a href="{{ url('/') }}" class="bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:opacity-90 transition">返回首页</a>
            <a href="{{ route('contact') }}" class="bg-white text-gray-700 border border-gray-200 px-6 py-2.5 rounded-lg font-medium hover:text-primary transition">联系我们</a>
        </div>
    </div>
</section>
@endsection
