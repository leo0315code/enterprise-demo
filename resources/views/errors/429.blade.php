@extends('layouts.app')

@section('title', '请求过于频繁')

@section('content')
<section class="py-28 bg-gray-bg">
    <div class="max-w-xl mx-auto px-4 text-center">
        <div class="text-7xl font-bold text-primary mb-4">429</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">请求过于频繁</h1>
        <p class="text-gray-500 mb-8">您的操作过于频繁，请稍后再试。</p>
        <a href="{{ url()->previous() ?: url('/') }}" class="bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:opacity-90 transition">返回上一页</a>
    </div>
</section>
@endsection
