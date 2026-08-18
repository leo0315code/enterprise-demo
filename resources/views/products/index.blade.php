@extends('layouts.app')

@section('title', '产品服务')

@section('content')
<section class="py-16 text-white" style="background-image: linear-gradient(to bottom right, var(--color-primary), var(--color-primary-dark));">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-3">产品服务</h1>
        <p class="text-blue-100">为您提供专业、可靠的解决方案</p>
    </div>
</section>

<section class="py-16 bg-gray-bg">
    <div class="max-w-7xl mx-auto px-4">
        {{-- 关键词搜索 --}}
        <form action="{{ route('products.index') }}" method="GET" class="max-w-md mx-auto mb-8">
            <div class="flex">
                @if($categorySlug)<input type="hidden" name="category" value="{{ $categorySlug }}">@endif
                <input type="search" name="q" value="{{ $keyword }}" placeholder="搜索产品名称或简介…"
                       class="flex-1 rounded-l-lg border border-r-0 border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                <button type="submit" class="bg-primary text-white px-6 rounded-r-lg font-medium hover:opacity-90 transition">搜索</button>
            </div>
        </form>

        @if($keyword !== '')
            <p class="text-center text-sm text-gray-500 mb-6">
                “{{ $keyword }}” 的搜索结果，共 {{ $products->total() }} 条
                <a href="{{ $categorySlug ? route('products.index', ['category' => $categorySlug]) : route('products.index') }}" class="text-primary hover:underline ml-2">清除搜索</a>
            </p>
        @endif

        <!-- 分类筛选 -->
        @if($categories->count())
        <div class="flex flex-wrap gap-3 justify-center mb-10">
            <a href="{{ route('products.index') }}"
               class="px-4 py-2 rounded-full text-sm {{ !$categorySlug ? 'bg-primary text-white' : 'bg-white text-gray-600 hover:text-primary' }}">全部</a>
            @foreach($categories as $cat)
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                   class="px-4 py-2 rounded-full text-sm {{ $categorySlug == $cat->slug ? 'bg-primary text-white' : 'bg-white text-gray-600 hover:text-primary' }}">{{ $cat->name }}</a>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product->slug) }}"
                   class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition group">
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
                <p class="col-span-3 text-center text-gray-400 py-12">{{ $keyword !== '' ? '未找到相关内容' : '暂无产品' }}</p>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection
