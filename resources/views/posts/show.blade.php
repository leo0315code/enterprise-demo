@extends('layouts.app')

@section('title', '文章详情')

@section('content')
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <a href="{{ route('posts.index') }}" class="text-primary hover:underline">← 返回新闻列表</a>
        <h1 class="text-3xl font-bold text-gray-900 mt-4 mb-6">文章详情</h1>
        <p class="text-gray-600">文章 slug: {{ $slug }}</p>
    </div>
</section>
@endsection
