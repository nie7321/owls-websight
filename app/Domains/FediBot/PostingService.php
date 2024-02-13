<?php

namespace App\Domains\FediBot;

use App\Domains\FediBot\Adapters\BotFactory;
use App\Domains\FediBot\Adapters\MastodonClient;
use App\Domains\FediBot\Entities\ActivityReport;
use App\Domains\FediBot\Entities\Post;
use App\Domains\FediBot\Models\Bot;
use App\Domains\FediBot\Models\PostHistory;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PostingService
{
    public function __construct(
        protected BotFactory $factory
    )
    {
        //
    }

    /**
     * @return Collection<Bot>
     */
    public function botsToCheck(?CarbonInterface $now = null): Collection
    {
        $now ??= Carbon::now();

        return Bot::query()
            ->where(function (Builder $builder) use ($now) {
                return $builder->whereNull('next_check_at')
                    ->orWhere('next_check_at', '<=', $now);
            })
            ->get();
    }

    /**
     * Fetches the items for a bot and logs them as if it had posted them. Useful when starting up a new bot so it
     * doesn't post 50 old items immediately.
     */
    public function initializeWithoutPosting(Bot $bot): ActivityReport
    {
        $backend = $this->factory->toInitializedBackend($bot);
        $fediClient = $this->factory->toClient($bot);

        $limits = $fediClient->limits();

        $backendPosts = $backend->postsFromFeed($bot->configuration, $limits);

        $bot->post_history()->createMany($backendPosts->map(function (Post $post) {
            return [
                'identifier' => $post->uniqueIdentifier,
                'content' => $post->formattedMessage
            ];
        }));

        return new ActivityReport(
            bot: $bot,
            newPosts: new Collection,
            allPostsFromBackend: $backendPosts,
        );
    }

    public function post(Bot $bot): ActivityReport
    {
        $backend = $this->factory->toInitializedBackend($bot);
        $fediClient = $this->factory->toClient($bot);

        $limits = $fediClient->limits();

        $backendPosts = $backend->postsFromFeed($bot->configuration, $limits);

        /** @var Collection $alreadyPostedIdentifiers */
        $alreadyPostedIdentifiers = $bot->post_history()
            ->getQuery()
            ->select('identifier')
            ->whereIn('identifier', $backendPosts->map->uniqueIdentifier)
            ->get()
            ->map->identifier;

        $newPosts = $backendPosts->reject(fn (Post $post) => $alreadyPostedIdentifiers->contains($post->uniqueIdentifier));

        $posted = [];
        foreach ($newPosts as $post) {
            $posted[] = $this->postStatus($bot, $fediClient, $post);
        }

        $bot->next_check_at = Carbon::now()->add(CarbonInterval::fromString($bot->check_frequency_interval));
        $bot->save();

        return new ActivityReport(
            bot: $bot,
            newPosts: collect($posted),
            allPostsFromBackend: $backendPosts,
        );
    }

    protected function postStatus(Bot $bot, MastodonClient $client, Post $post): PostHistory
    {
        $client->createStatus($post->formattedMessage);

        /** @var PostHistory $record */
        $record = $bot->post_history()->create([
            'identifier' => $post->uniqueIdentifier,
            'content' => $post->formattedMessage
        ]);

        return $record;
    }
}
