<?php

namespace App\Console\Commands;

use App\Domains\Opml\Actions\CanonicalizeFeedUrl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CanonicalizeOpmlFeedURLs extends Command
{
    protected $signature = 'opml:canonicalizer';
    protected $description = 'Updates OPML files with canonical xmlFeed URLs and republishes them.';

    public function handle(CanonicalizeFeedUrl $cannonicalizer): int
    {
        $opmlUrl = $this->argument('url');
        $filename = $this->argument('outputFilename');

        $updatedOpml = $cannonicalizer->forOpml($opmlUrl);

        $url = $this->writeToDisk($filename, $updatedOpml->asXML());

        $this->newLine();
        $this->info("Finished processing.");
        $this->newLine();
        $this->info($url);

        return self::SUCCESS;
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
