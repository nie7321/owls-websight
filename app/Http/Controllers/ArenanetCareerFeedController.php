<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\AshbyJobMonitor\Actions\LoadAshbyFeed;
use Illuminate\Http\Response;

class ArenanetCareerFeedController extends Controller
{
    public function __construct() {
        $this->middleware('throttle');
    }

    public function __invoke(): Response
    {
        $ashbyFeed = new LoadAshbyFeed('arenanet');

        $posts = $ashbyFeed->posts();

        return response()
            ->view('external-feeds.anet-atom', [
                'jobs' => $posts,
            ])
            ->header('Content-Type', 'application/rss+xml');
    }
}
