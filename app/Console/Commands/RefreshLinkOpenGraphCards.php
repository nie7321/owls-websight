<?php

namespace App\Console\Commands;

use App\Domains\Blog\Models\Link;
use App\Domains\OpenGraph\Actions\CacheOpenGraphImage;
use App\Domains\OpenGraph\Actions\FetchOpenGraph;
use Carbon\CarbonInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RefreshLinkOpenGraphCards extends Command
{
    protected $signature = 'blog:refresh-link-cards';
    protected $description = 'Refreshes the link card metadata for auto-updating cards.';

    public function __construct(
        protected FetchOpenGraph $openGraph,
        protected CacheOpenGraphImage $ogImageCacher,
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        $linksToRefresh = Link::readyForRefresh($now)->get();

        if (! $linksToRefresh->count()) {
            $this->line('No cards to refresh');

            return self::SUCCESS;
        }

        $this->info("Preparing to refresh {$linksToRefresh->count()} links:");
        $this->newLine();

        $statusRows = $linksToRefresh->map(fn (Link $link) => [
            $link->url,
            $link->card_last_polled_at ? $link->card_last_polled_at->diffForHumans($now, CarbonInterface::DIFF_RELATIVE_TO_NOW) : 'never'
        ]);
        $this->table(['Link', 'Last Refreshed At'], $statusRows->all());

        $results = [];
        foreach ($linksToRefresh as $link) {
            $newLink = $this->refresh($link);

            $results[] = [
                $link->url,
                $newLink ? 'OK' : 'Error',
                $newLink->title,
            ];
        }

        $this->newLine();
        $this->info('Links updated!');
        $this->newLine();

        $this->table(['Link', 'Status', 'New Title'], $results);
        return self::SUCCESS;
    }

    private function refresh(Link $link): ?Link
    {
        $metadata = $this->openGraph->fetch($link->url);
        if (! $metadata) {
            return null;
        }

        $cardImage = null;
        if ($metadata->imageUrl) {
            $cardImage = ($this->ogImageCacher)($metadata->imageUrl);
        }

        $link->update([
            'title' => $metadata->title,
            'description' => $metadata->description,
            'card_image_path' => $cardImage,
            'card_last_polled_at' => Carbon::now(),
        ]);

        return $link;
    }
}
