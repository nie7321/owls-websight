<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\AshbyJobMonitor\Actions\LoadAshbyFeed;
use App\Domains\AshbyJobMonitor\Entities\AshbyJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ArenanetCareerFeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle');
    }

    public function __invoke(Request $request): Response
    {
        $ashbyFeed = new LoadAshbyFeed('arenanet');

        $filters = $request->validate([
            'keywords' => 'nullable|string',
        ]);

        $keywords = $filters['keywords'] ?? null;

        $posts = $ashbyFeed->posts();

        if ($keywords) {
            $keywords = collect(explode(',', $keywords))
                ->map(trim(...))
                ->map(strtolower(...));

            $posts = $posts->filter(function (AshbyJob $job) use ($keywords) {
                if (Str::contains(strtolower($job->title), $keywords)) {
                    return true;
                }

                if (Str::contains(strtolower($job->plainDescription), $keywords)) {
                    return true;
                }

                return false;
            });
        }

        return response()
            ->view('external-feeds.anet-atom', [
                'jobs' => $posts,
            ])
            ->header('Content-Type', 'application/rss+xml');
    }
}
