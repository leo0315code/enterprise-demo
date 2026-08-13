@extends('layouts.app')

@section('content')
<section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-24">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            {{ setting('site_name', '企业官网') }}
        </h1>
        <p class="text-xl text-gray-600 mb-8">{{ setting('site_slogan', '') }}</p>
        <a href="{{ route('contact') }}" class="bg-primary text-white px-8 py-3 rounded-lg font-medium hover:opacity-90 transition">
            联系我们
        </a>
    </div>
</section>
@endsection
