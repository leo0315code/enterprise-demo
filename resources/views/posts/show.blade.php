@extends('layouts.app')

@section('title', $post->title)

@section('description', $post->summary ?? '')

@section('canonical', route('posts.show', $post->slug))

@section('og_type', 'article')

@if($post->cover)
@section('og_image', url($post->cover))
@endif

@push('jsonld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "Article",
    "headline": @json($post->title),
    "description": @json($post->summary ?? ''),
    "datePublished": @json(($post->published_at ?? $post->created_at)->toIso8601String()),
    "dateModified": @json($post->updated_at->toIso8601String()),
    @if($post->author)
    "author": {"@type": "Person", "name": @json($post->author)},
    @endif
    "mainEntityOfPage": @json(route('posts.show', $post->slug))
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "首页", "item": @json(url('/'))},
        {"@type": "ListItem", "position": 2, "name": "新闻动态", "item": @json(route('posts.index'))},
        {"@type": "ListItem", "position": 3, "name": @json($post->title)}
    ]
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
            <a href="{{ route('posts.index') }}" class="hover:text-primary">新闻动态</a>
            <span class="mx-1">/</span>
            <span class="text-gray-600">{{ $post->title }}</span>
        </nav>

        <div class="text-sm text-gray-400 mb-2">
            {{ $post->published_at?->format('Y-m-d') ?? $post->created_at->format('Y-m-d') }}
            @if($post->category) · {{ $post->category->name }} @endif
            @if($post->author) · {{ $post->author }} @endif
            · {{ number_format($post->views) }} 次浏览
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $post->title }}</h1>

        @if($post->cover)
            <img src="{{ $post->cover }}" alt="{{ $post->title }}" class="w-full rounded-xl mb-8" loading="lazy">
        @endif

        <div class="prose-content">
            {!! $post->content !!}
        </div>

        {{-- 上一篇 / 下一篇 --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-12 pt-8 border-t border-gray-100">
            @if($prevPost)
                <a href="{{ route('posts.show', $prevPost->slug) }}" class="block bg-gray-bg rounded-lg p-4 hover:shadow-sm transition">
                    <div class="text-xs text-gray-400 mb-1">← 上一篇</div>
                    <div class="font-medium text-gray-900 line-clamp-1">{{ $prevPost->title }}</div>
                </a>
            @else
                <div class="hidden sm:block"></div>
            @endif
            @if($nextPost)
                <a href="{{ route('posts.show', $nextPost->slug) }}" class="block bg-gray-bg rounded-lg p-4 hover:shadow-sm transition text-right">
                    <div class="text-xs text-gray-400 mb-1">下一篇 →</div>
                    <div class="font-medium text-gray-900 line-clamp-1">{{ $nextPost->title }}</div>
                </a>
            @endif
        </div>
    </div>
</section>

{{-- 相关文章 --}}
@if($relatedPosts->count())
<section class="py-14 bg-gray-bg">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-xl font-bold text-gray-900 mb-6">相关文章</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            @foreach($relatedPosts as $related)
                <a href="{{ route('posts.show', $related->slug) }}"
                   class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition group">
                    <div class="text-xs text-gray-400 mb-2">{{ $related->published_at?->format('Y-m-d') ?? $related->created_at->format('Y-m-d') }}</div>
                    <h3 class="font-medium text-gray-900 group-hover:text-primary transition line-clamp-2">{{ $related->title }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
