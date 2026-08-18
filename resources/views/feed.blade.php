<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ setting('site_name', config('app.name')) }}</title>
        <link>{{ url('/') }}</link>
        <description>{{ setting('seo_description', '') }}</description>
        <language>zh-cn</language>
        <lastBuildDate>{{ ($posts->first()?->updated_at ?? now())->toRssString() }}</lastBuildDate>
        <atom:link href="{{ route('feed') }}" rel="self" type="application/rss+xml"/>
@foreach($posts as $post)
        <item>
            <title>{{ $post->title }}</title>
            <link>{{ route('posts.show', $post->slug) }}</link>
            <guid>{{ route('posts.show', $post->slug) }}</guid>
            <pubDate>{{ ($post->published_at ?? $post->created_at)->toRssString() }}</pubDate>
            <description><![CDATA[{{ $post->summary ?: \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 200) }}]]></description>
        </item>
@endforeach
    </channel>
</rss>
