<?php

declare(strict_types=1);

namespace App\Domains\RssDiscovery\Entities;

use App\Domains\RssDiscovery\Types\FeedType;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

readonly class FeedDiscoveryResult
{
    /**
     * @param Collection<DiscoveredFeed> $feeds
     */
    public function __construct(
        public ?string $siteName,
        public string $siteUrl,
        public Collection $feeds,
        public bool $encounteredError,
        public ?string $error = null,
    ) {
        //
    }

    /**
     * Returns the "best" feed for the site.
     *
     * This has some logic to it -- WordPress sites usually have a post feed and a comments feed.
     * We want the post feed.
     */
    public function bestFeed(): ?DiscoveredFeed
    {
        $manualOverride = self::overrideFeed($this->siteUrl);
        if ($manualOverride) {
            return $manualOverride;
        }

        return $this->feeds->sortByDesc('score')->first();
    }

    /**
     * Some websites either do not expose their feed as a <link>, are nested another layer down from the site on the
     * blaugust list, or link to the wrong place.
     *
     * This is so I don't have to keep fixing them the hard way.
     */
    static public function overrideFeed(string $siteUrl): ?DiscoveredFeed
    {
        $lookup = [
            'https://mattbee.zone' => 'https://mattbee.zone/rss.xml',
            'https://www.achilletoupin.com' => 'https://www.achilletoupin.com/feed.xml',
            'https://axxuy.xyz' => 'https://axxuy.xyz/blog/feed.xml',
            'https://aywren.com' => 'https://aywren.com/feed.xml',
            'https://chaosgoat.neocities.org' => 'https://chaosgoat.neocities.org/feed.xml',
            'https://cobb.land' => 'https://cobb.land/feed.xml',
            'https://www.conor.zone/en/blog/' => 'https://www.conor.zone/en/feeds/atom.xml',
            'https://divergentrays.com/blog/' => 'https://divergentrays.com/blog/blogfeed.xml',
            'https://fiat-mihi.com' => 'https://fiat-mihi.com/rss.xml',
            'https://glome.bearblog.dev/' => 'https://glome.bearblog.dev/feed/',
            'https://thekeerok.neocities.org/' => 'https://punkto.org/zonerender?https://thekeerok.neocities.org/archive',
            'https://lunarloony.co.uk' => 'https://lunarloony.co.uk/feed/',
            'https://youtube.com/@magiwastaken' => 'https://indiecator.org/feed/',
            'https://neurofrontiers.blog' => 'https://neurofrontiers.blog/feed/',
            'https://notes.druchan.com' => 'https://notes.druchan.com/feed.xml',
            'https://rseeber.github.io/blog/' => 'https://rseeber.github.io/blog/feed.xml',
            'https://sag.sadesignz.org/' => 'https://sag.sadesignz.org/feed/',
            'https://splendide-mendax.com' => 'https://splendide-mendax.com/rss.xml',
            'https://taxodium.ink' => 'https://taxodium.ink/rss.xml',
            'https://aggronaut.com' => 'https://aggronaut.com/feed/',
            'https://lapislabel.net' => 'https://lapislabel.net/feed.xml',
            'https://hollie.eilloh.net/' => 'https://hollie.eilloh.net/rss.xml',
            'https://davehenry.blog/' => 'https://davehenry.blog/atom.xml',
            'https://kimberlygb.nekoweb.org/' => 'https://kimberlygb.nekoweb.org/feed.xml',
            'https://your-local-grubdog.neocities.org/' => 'https://your-local-grubdog.neocities.org/feed.xml',
            'https://gridranger.frama.io/' => 'https://gridranger.frama.io/feeds/all.atom.xml',
            'https://z.arlmy.me' => 'https://z.arlmy.me/atom.xml',
            'https://skywalker.works/blog/' => 'http://skywalker.works/blog/feed/', // tls not configured
            'https://thelosophy.net' => 'https://thelosophy.net/rss.xml',
        ];

        $feedUrl = Arr::get($lookup, $siteUrl);
        if (! $feedUrl) {
            return null;
        }

        return new DiscoveredFeed(
            title: null,
            type: FeedType::RSS,
            url: $feedUrl,
        );
    }
}
