<?php

declare(strict_types=1);

namespace App\Domains\AshbyJobMonitor\Actions;

use App\Domains\AshbyJobMonitor\Entities\AshbyJob;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class LoadAshbyFeed
{
    protected const BASE_URL = 'https://api.ashbyhq.com/posting-api/job-board';

    protected string $boardUrl;

    public function __construct(protected string $boardSlug)
    {
        $this->boardUrl = self::BASE_URL . '/' . $this->boardSlug;
    }

    /**
     * @return Collection<AshbyJob>
     */
    public function posts(): Collection
    {
        return collect($this->get())
            ->map(function (array $job) {
                return new AshbyJob(
                    uuid: $job['id'],
                    publishedAt: CarbonImmutable::parse($job['publishedAt']),
                    title: $job['title'],
                    postUrl: $job['jobUrl'],
                    applyUrl: $job['applyUrl'],
                    htmlDescription: $job['descriptionHtml'],
                    plainDescription: $job['descriptionPlain'],
                );
            });
    }

    private function get(): array
    {
        return Http::retry(3, 250)
            ->acceptJson()
            ->throw()
            ->get($this->boardUrl)
            ->json('jobs');
    }
}
