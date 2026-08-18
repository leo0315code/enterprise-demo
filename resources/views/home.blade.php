@extends('layouts.app')

@push('jsonld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "Organization",
    "name": @json(setting('site_name', config('app.name'))),
    "description": @json(setting('site_description', '')),
    "url": @json(url('/'))
    @if(setting('contact_phone'))
    ,"telephone": @json(setting('contact_phone'))
    @endif
    @if(setting('contact_address'))
    ,"address": @json(setting('contact_address'))
    @endif
}
</script>
@endpush

@section('content')
@foreach($sections as $section)
    @switch($section->type)

        {{-- Hero Banner --}}
        @case('hero')
            <section class="relative text-white overflow-hidden" style="background-image: linear-gradient(to bottom right, var(--color-primary), var(--color-primary-dark));">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_20%_20%,white,transparent)]"></div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ $section->title }}</h1>
                    @if($section->subtitle)
                        <p class="text-lg md:text-xl text-blue-100 mb-10 max-w-3xl mx-auto">{{ $section->subtitle }}</p>
                    @endif
                    @if($section->button_text)
                        <a href="{{ $section->button_link ?? '#' }}"
                           class="inline-flex bg-white text-blue-700 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition shadow-lg">
                            {{ $section->button_text }}
                        </a>
                    @endif
                </div>
            </section>
            @break

        {{-- 公司简介 --}}
        @case('intro')
            <section class="py-20 bg-white">
                <div class="max-w-4xl mx-auto px-4 text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ $section->title }}</h2>
                    @if($section->subtitle)
                        <p class="text-primary font-medium mb-6">{{ $section->subtitle }}</p>
                    @endif
                    <div class="prose-content text-gray-600 text-left mx-auto">{!! $section->content !!}</div>
                    @if($section->button_text)
                        <a href="{{ $section->button_link ?? '#' }}"
                           class="inline-flex mt-8 bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:opacity-90 transition">
                            {{ $section->button_text }}
                        </a>
                    @endif
                </div>
            </section>
            @break

        {{-- 核心优势（卡片网格） --}}
        @case('features')
            <section class="py-20 bg-gray-bg">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ $section->title }}</h2>
                        @if($section->subtitle)
                            <p class="text-gray-500">{{ $section->subtitle }}</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach(($section->extra ?? []) as $item)
                            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition text-center">
                                <div class="text-4xl mb-3">{{ $item['icon'] ?? '⭐' }}</div>
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $item['title'] ?? '' }}</h3>
                                <p class="text-sm text-gray-500">{{ $item['desc'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @break

        {{-- 推荐产品 --}}
        @case('products')
            <section class="py-20 bg-white">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ $section->title }}</h2>
                        @if($section->subtitle)
                            <p class="text-gray-500">{{ $section->subtitle }}</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($featuredProducts as $product)
                            <a href="{{ route('products.show', $product->slug) }}"
                               class="bg-gray-bg rounded-xl overflow-hidden shadow-sm hover:shadow-md transition group">
                                @if($product->thumbnail)
                                    <img src="{{ $product->thumbnail }}" alt="{{ $product->title }}" class="w-full h-48 object-cover" loading="lazy">
                                @else
                                    <div class="w-full h-48 bg-primary/10 flex items-center justify-center text-5xl text-primary">📦</div>
                                @endif
                                <div class="p-5">
                                    <h3 class="font-semibold text-gray-900 group-hover:text-primary transition">{{ $product->title }}</h3>
                                    @if($product->summary)
                                        <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $product->summary }}</p>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="col-span-3 text-center text-gray-400">暂无产品</p>
                        @endforelse
                    </div>
                    <div class="text-center mt-10">
                        <a href="{{ route('products.index') }}" class="text-primary font-medium hover:underline">查看全部产品 →</a>
                    </div>
                </div>
            </section>
            @break

        {{-- 最新新闻 --}}
        @case('news')
            <section class="py-20 bg-gray-bg">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ $section->title }}</h2>
                        @if($section->subtitle)
                            <p class="text-gray-500">{{ $section->subtitle }}</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($latestPosts as $post)
                            <a href="{{ route('posts.show', $post->slug) }}"
                               class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition group">
                                @if($post->cover)
                                    <img src="{{ $post->cover }}" alt="{{ $post->title }}" class="w-full h-48 object-cover" loading="lazy">
                                @else
                                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-5xl">📰</div>
                                @endif
                                <div class="p-5">
                                    <div class="text-xs text-gray-400 mb-2">{{ $post->published_at?->format('Y-m-d') ?? $post->created_at->format('Y-m-d') }}</div>
                                    <h3 class="font-semibold text-gray-900 group-hover:text-primary transition line-clamp-2">{{ $post->title }}</h3>
                                </div>
                            </a>
                        @empty
                            <p class="col-span-3 text-center text-gray-400">暂无文章</p>
                        @endforelse
                    </div>
                    <div class="text-center mt-10">
                        <a href="{{ route('posts.index') }}" class="text-primary font-medium hover:underline">查看全部新闻 →</a>
                    </div>
                </div>
            </section>
            @break

        {{-- CTA 行动召唤 --}}
        @case('cta')
            <section class="py-16 bg-primary">
                <div class="max-w-4xl mx-auto px-4 text-center text-white">
                    <h2 class="text-3xl font-bold mb-3">{{ $section->title }}</h2>
                    @if($section->subtitle)
                        <p class="text-blue-100 mb-8">{{ $section->subtitle }}</p>
                    @endif
                    @if($section->button_text)
                        <a href="{{ $section->button_link ?? '#' }}"
                           class="inline-flex bg-white text-blue-700 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                            {{ $section->button_text }}
                        </a>
                    @endif
                </div>
            </section>
            @break

        {{-- 自定义内容 --}}
        @case('custom')
            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4">
                    @if($section->title)
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">{{ $section->title }}</h2>
                    @endif
                    <div class="prose-content mx-auto">{!! $section->content !!}</div>
                </div>
            </section>
            @break
    @endswitch
@endforeach
@endsection
