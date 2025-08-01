<?php

namespace App\Console\Commands;

use App\Domains\RssDiscovery\Actions\DiscoverSiteFeed;
use Dom\HTMLCollection;
use Dom\HTMLDocument;
use Exception;
use Generator;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Throwable;

class DiscoverRssFeeds extends Command
{
    protected $signature = 'opml:discover-feeds {path : Path to a file with "Blog URL,Site Label", one pair per line}';
    protected $description = 'Attempt to find an RSS feed URL for a website';

    public function handle(DiscoverSiteFeed $discoverSiteFeed)
    {
        $path = $this->argument('path');

        $this->info(implode(',', ["Site", "Base URL", "Found?", "Feed Title", "Feed Type", "Feed URL", "OPML"]));

        foreach ($this->urls($path) as $url => $siteLabel) {
            try {
                $result = $discoverSiteFeed->for($siteLabel, $url);
            } catch (Throwable) {
                $this->info(implode(',', [$siteLabel, $url, 'No']));

                continue;
            }

            $feed = $result->bestFeed();

            $this->info(implode(',', [
                $siteLabel,
                $url,
                $feed ? 'Yes' : 'No',
                $feed?->title ? "\"{$feed->title}\"" : null,
                $feed?->type->value,
                $feed?->url,
                $feed?->opmlOutline($siteLabel, $url),
            ]));
        }
    }

    private function urls(string $path): Generator
    {
        $urlList = file_get_contents($path);
        throw_if($urlList === false, new Exception('File not found'));

        $sites = explode("\n", $urlList);
        foreach ($sites as $line) {
            [$url, $label] = explode(',', $line);

            yield trim($url) => trim($label);
        }
    }
}
