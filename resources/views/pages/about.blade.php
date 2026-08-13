@extends('layouts.app')

@section('title', '关于我们')

@section('content')
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">关于我们</h1>
        <p class="text-gray-600 leading-relaxed">{{ setting('site_description', '') }}</p>
    </div>
</section>
@endsection
