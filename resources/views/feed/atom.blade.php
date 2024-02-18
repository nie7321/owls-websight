<?php
// Otherwise IDEs or PHP runtimes with short tags enabled will think <? means it's PHP time.
// {{-- prettier-ignore --}}
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:media="http://search.yahoo.com/mrss/"
>
    <channel>
        <atom:link type="application/rss+xml" href="{{ route('feed.atom') }}" rel="self" />
        <title><![CDATA[{{ config('app.name') }}]]></title>
        <link>{{ route('blog-post.index') }}</link>
        <description>posts from owls</description>
        <language>en</language>
        <pubDate>{{ now()->toRssString() }}</pubDate>

        <sy:updatePeriod>hourly</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>

        <image>
            <url>{{ asset('image/owls-avatar.png') }}</url>
            <title><![CDATA[{{ config('app.name') }}]]></title>
            <link>{{ route('blog-post.index') }}</link>
            <width>32</width>
            <height>32</height>
        </image>

        @php /** @var \App\Domains\Blog\Models\BlogPost $post */@endphp
        @foreach ($posts as $post)
            <item>
                <title><![CDATA[{{ $post->title }}]]></title>
                <link>{{ $post->permalink }}</link>
                <dc:creator><![CDATA[$post->author->name]]></dc:creator>
                <description><![CDATA[{!! $summaryRenderer->convert($post->summary) !!}]]></description>
                <content:encoded><![CDATA[{!! $postRenderer->convert($post->content) !!}]]></content:encoded>
                <guid isPermaLink="true">{{ $post->permalink }}</guid>
                <pubDate>{{ $post->published_at->toRssString() }}</pubDate>

                @if($post->thumbnail_image)
                    <media:thumbnail url="{{ $post->thumbnail_image->getFirstMedia()->getUrl('preview') }}" />
                @endif
            </item>
        @endforeach
    </channel>
</rss>
