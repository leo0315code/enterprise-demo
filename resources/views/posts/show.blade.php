@extends('layouts.app')

@section('title', $post->title)

@section('description', $post->summary)

@section('content')
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <a href="{{ route('posts.index') }}" class="text-primary hover:underline">← 返回新闻列表</a>

        <div class="text-sm text-gray-400 mt-4 mb-2">
            {{ $post->published_at?->format('Y-m-d') ?? $post->created_at->format('Y-m-d') }}
            @if($post->category) · {{ $post->category->name }} @endif
            @if($post->author) · {{ $post->author }} @endif
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $post->title }}</h1>

        @if($post->cover)
            <img src="{{ $post->cover }}" alt="{{ $post->title }}" class="w-full rounded-xl mb-8">
        @endif

        <div class="prose-content">
            {!! $post->content !!}
        </div>
    </div>
</section>
@endsection
