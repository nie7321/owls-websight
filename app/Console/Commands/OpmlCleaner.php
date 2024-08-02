<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class OpmlCleaner extends Command
{
    protected $signature = 'rss:opml-cleaner {url} {outputFilename}';
    protected $description = 'Cleans an OPML file and drops it in the public folder';

    public function handle(): int
    {
        $opmlUrl = $this->argument('url');
        $filename = $this->argument('outputFilename');

        $opml = $this->getOpml($opmlUrl);
        if (! $opml->body?->outline) {
            $this->error('No outline entries found');
            return 1;
        }

        foreach ($opml->body->outline->children() as $outline) {
            $this->info("Processing {$outline['title']}...");

            $cannonicalUrl = $this->getCannonicalFeedUrl($outline['xmlUrl']);
            if (! $cannonicalUrl) {
                // If this failed (site down or w/e), then ... idk do nothing.
                continue;
            }

            if ((string) $outline['xmlUrl'] !== $cannonicalUrl) {
                $this->info("\tChanged URL!");
            }

            $outline['xmlUrl'] = $cannonicalUrl;
        }

        $url = $this->writeToDisk($filename, $opml->asXML());

        $this->newLine();
        $this->info("Finished processing.");
        $this->newLine();
        $this->info($url);

        return self::SUCCESS;
    }

    private function getOpml(string $url): SimpleXMLElement
    {
        $resp = Http::get($url);
        if (! $resp->successful()) {
            $resp->throw();
        }

        // Any & in URLs will cause the XML parsers to choke.
        $cleanedOpml = preg_replace("|&([^;]+?)[\s<&]|","&#038;$1 ",$resp->body());

        return simplexml_load_string($cleanedOpml);
    }

    private function getCannonicalFeedUrl(string $url): ?string
    {
        try {
            $resp = Http::withOptions(['allow_redirects' => false])->get($url);

            if ($resp->redirect()) {
                return $this->getCannonicalFeedUrl($resp->header('Location'));
            }

            if ($resp->successful()) {
                return $url;
            }
        } catch (ConnectionException $e) {
            // do nothing, let the null below handle it.
        }

        return null;
    }

    private function writeToDisk(string $filename, string $opmlString): string
    {
        $disk = Storage::disk('public');

        if (! $disk->directoryExists('opml')) {
            $disk->createDirectory('opml');
        }

        $path = "opml/{$filename}";
        $disk->put($path, $opmlString);

        return $disk->url($path);
    }
}
