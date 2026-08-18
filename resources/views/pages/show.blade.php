@extends('layouts.app')

@section('title', $page->seo_title ?: $page->title)

@section('description', $page->seo_description ?? '')

@section('canonical', route('page.show', $page->slug))

@push('jsonld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "首页", "item": @json(url('/'))},
        {"@type": "ListItem", "position": 2, "name": @json($page->title)}
    ]
}
</script>
@endpush

@section('content')
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <nav class="text-sm text-gray-400 mb-6 text-center" aria-label="面包屑">
            <a href="{{ url('/') }}" class="hover:text-primary">首页</a>
            <span class="mx-1">/</span>
            <span class="text-gray-600">{{ $page->title }}</span>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">{{ $page->title }}</h1>
        <div class="prose-content">
            {!! $page->content !!}
        </div>
    </div>
</section>
@endsection
