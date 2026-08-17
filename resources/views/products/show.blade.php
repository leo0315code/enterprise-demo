@extends('layouts.app')

@section('title', $product->title)

@section('content')
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <a href="{{ route('products.index') }}" class="text-primary hover:underline">← 返回产品列表</a>

        <h1 class="text-3xl font-bold text-gray-900 mt-4 mb-6">{{ $product->title }}</h1>

        @if($product->thumbnail)
            <img src="{{ $product->thumbnail }}" alt="{{ $product->title }}" class="w-full rounded-xl mb-8" loading="lazy">
        @endif

        @if($product->summary)
            <p class="text-lg text-gray-600 mb-6">{{ $product->summary }}</p>
        @endif

        <div class="prose-content">
            {!! $product->content !!}
        </div>
    </div>
</section>
@endsection
