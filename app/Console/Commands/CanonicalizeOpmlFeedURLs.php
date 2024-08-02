<?php

namespace App\Console\Commands;

use App\Domains\Opml\Actions\CanonicalizeFeedUrl;
use App\Domains\Opml\Models\ExternalOpmlList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CanonicalizeOpmlFeedURLs extends Command
{
    protected $signature = 'opml:canonicalizer';
    protected $description = 'Updates OPML files with canonical xmlFeed URLs and republishes them.';

    public function handle(CanonicalizeFeedUrl $cannonicalizer): int
    {
        $opmlFiles = ExternalOpmlList::whereActive(true)->get();

        if (! $opmlFiles->count()) {
            $this->info('No OPML lists to process.');

            return self::SUCCESS;
        }

        $reportHeaders = ["OPML", "URL"];
        $reportRows = [];

        $this->info("Processing {$opmlFiles->count()} lists...");
        $this->newLine();

        foreach ($opmlFiles as $opmlFile) {
            $updatedOpml = $cannonicalizer->forOpml($opmlFile->url, $opmlFile->docs_url);
            $this->writeToDisk($opmlFile, $updatedOpml->asXML());

            $reportRows[] = [$opmlFile->label, $opmlFile->republished_url];
        }

        $this->table($reportHeaders, $reportRows);

        return self::SUCCESS;
    }

    private function writeToDisk(ExternalOpmlList $opmlFile, string $opmlString): void
    {
        $disk = Storage::disk('public');

        if (! $disk->directoryExists('opml')) {
            $disk->createDirectory('opml');
        }

        $disk->put($opmlFile->opml_disk_path, $opmlString);
    }
}
