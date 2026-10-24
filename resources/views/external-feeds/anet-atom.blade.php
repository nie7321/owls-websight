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
        <atom:link type="application/rss+xml" href="{{ route('anet-jobs') }}" rel="self" />
        <title>Arenanet Job Board</title>
        <link>https://jobs.ashbyhq.com/arenanet/</link>
        <description>Atom version of Arenanet's Ashby career board</description>
        <language>en</language>
        <pubDate>{{ now()->toRssString() }}</pubDate>

        <sy:updatePeriod>hourly</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>

        <icon>https://cdn.ashbyprd.com/cdn_assets/ef346a6e403e6557b5b1cfff2b45a93904340c14/favicon.png</icon>
        <logo>https://app.ashbyhq.com/api/images/org-theme-wordmark/a6b4b333-91ce-4c96-aaaa-a70cece089e2/4406656d-6cf8-4e1b-9e56-8fe1252c2880/09fa6e64-5761-4483-a994-911c0732db18.png</logo>

        @php /** @var \App\Domains\AshbyJobMonitor\Entities\AshbyJob $job */@endphp
        @foreach ($jobs as $job)
            <item>
                <title><![CDATA[{{ $job->title }}]]></title>
                <link>{{ $job->postUrl }}</link>
                <description><![CDATA[{!! $job->htmlDescription !!}]]></description>
                <guid isPermaLink="true">{{ $job->postUrl }}</guid>
                <pubDate>{{ $job->publishedAt->toRssString() }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>
