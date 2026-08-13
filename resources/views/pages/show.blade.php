@extends('layouts.app')

@section('title', $page->seo_title ?: $page->title)

@section('description', $page->seo_description)

@section('content')
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">{{ $page->title }}</h1>
        <div class="prose-content">
            {!! $page->content !!}
        </div>
    </div>
</section>
@endsection
