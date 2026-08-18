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

{{-- 相关产品 --}}
@if($relatedProducts->count())
<section class="py-14 bg-gray-bg">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-xl font-bold text-gray-900 mb-6">相关产品</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            @foreach($relatedProducts as $related)
                <a href="{{ route('products.show', $related->slug) }}"
                   class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition group">
                    @if($related->thumbnail)
                        <img src="{{ thumb_url($related->thumbnail) }}" alt="{{ $related->title }}" class="w-full h-32 object-cover" loading="lazy">
                    @else
                        <div class="w-full h-32 bg-primary/10 flex items-center justify-center text-3xl text-primary">📦</div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 group-hover:text-primary transition line-clamp-1">{{ $related->title }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
