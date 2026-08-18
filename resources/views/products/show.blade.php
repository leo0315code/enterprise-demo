@extends('layouts.app')

@section('title', $product->title)

@section('description', $product->summary ?? '')

@section('canonical', route('products.show', $product->slug))

@if($product->thumbnail)
@section('og_image', url($product->thumbnail))
@endif

@push('jsonld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "Product",
    "name": @json($product->title),
    "description": @json($product->summary ?? ''),
    @if($product->thumbnail)
    "image": @json(url($product->thumbnail)),
    @endif
    "url": @json(route('products.show', $product->slug))
}
</script>
@endpush

@section('content')
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        {{-- 面包屑 --}}
        <nav class="text-sm text-gray-400 mb-6" aria-label="面包屑">
            <a href="{{ url('/') }}" class="hover:text-primary">首页</a>
            <span class="mx-1">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-primary">产品服务</a>
            <span class="mx-1">/</span>
            <span class="text-gray-600">{{ $product->title }}</span>
        </nav>

        <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $product->title }}</h1>

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
