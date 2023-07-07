<?php

namespace App\Console\Commands;

use App\Domains\FediBot\Models\Bot;
use App\Domains\FediBot\PostingService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class MakeFediBotPosts extends Command
{
    protected $signature = 'fedi-bot:post {--force}';
    protected $description = 'Checks bot backends for new content & posts it.';

    public function handle(PostingService $postingService)
    {
        $force = (bool) $this->option('force');

        $bots = ! $force
            ? $postingService->botsToCheck()
            : Bot::all();

        $results = [];
        foreach ($bots as $bot) {
            $activity = $postingService->post($bot);

            $results[] = [
                $bot->username,
                Arr::get($activity->toArray(), 'newPosts.count'),
                $bot->next_check_at
            ];
        }

        if ($bots->count() > 0) {
            $this->table(['Bot', 'Posts Made', 'Next Check'], $results);
        } else {
            $this->info('No bots to check!');
            $this->newLine();

            $this->table(['Bot', 'Next Check'], Bot::all()->map(fn (Bot $bot) => [$bot->username, $bot->next_check_at]));
        }

        return self::SUCCESS;
    }
}
