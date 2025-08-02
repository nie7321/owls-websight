<?php

namespace App\Console\Commands;

use App\Domains\RssDiscovery\Actions\DiscoverSiteFeed;
use App\Domains\RssDiscovery\Actions\OpmlWriter;
use App\Domains\RssDiscovery\Entities\DiscoveredFeed;
use App\Domains\RssDiscovery\Entities\FeedDiscoveryResult;
use Dom\HTMLCollection;
use Dom\HTMLDocument;
use Exception;
use Generator;
use Illuminate\Console\Command;
use Throwable;

class DiscoverRssFeeds extends Command
{
    protected $signature = 'opml:discover-feeds {path : Path to a file with "Blog URL,Site Label", one pair per line} {output : Filename (for the public dir) to output }';
    protected $description = 'Attempt to find an RSS feed URL for a website';

    public function handle(DiscoverSiteFeed $discoverSiteFeed, OpmlWriter $writer)
    {
        $path = $this->argument('path');
        $outputFile = $this->argument('output');

        $found = [];
        $notFound = [];
        $opmlTags = [];

        foreach ($this->urls($path) as $url => $siteLabel) {
            $result = $discoverSiteFeed->for($siteLabel, $url);

            if ($result->encounteredError) {
                $this->error("Error for {$siteLabel} <{$url}>");
                $this->error($result->error);
            }

            $feed = $result->bestFeed();

            if (! $feed) {
                $notFound[] = [$siteLabel, $url];
                continue;
            }

            $found[] = [$siteLabel, $url, $feed->url];
            $opmlTags[] = $feed->opmlOutline($siteLabel, $url);
        }

        $writer->toXml(public_path($outputFile), $opmlTags);

        $this->newLine(3);

        $this->info("Sites found:");
        $this->newLine();
        $this->table(["Site", "URL", "Feed"], $found);

        $this->newLine(2);
        $this->error("Sites not found:");
        $this->newLine();
        $this->table(["Site", "URL"], $notFound);
    }

    private function urls(string $path): Generator
    {
        $urlList = trim(file_get_contents($path));
        throw_if($urlList === false, new Exception('File not found'));

        $sites = explode("\n", $urlList);
        foreach ($sites as $line) {
            $line = preg_replace('/<li><a href="/i', '', $line);
            $line = preg_replace('/">/', ',', $line);
            $line = preg_replace('/<\/a><\/li>/i', '', $line);
            $line = preg_replace('/\*\s*$/', '', $line);

            [$url, $label] = explode(',', $line);

            $url = trim($url);
            $label = trim($label);

            if (! str_starts_with($url, 'http')) {
                $url = "https://{$url}";
            }

            yield $url => $label;
        }
    }
}
