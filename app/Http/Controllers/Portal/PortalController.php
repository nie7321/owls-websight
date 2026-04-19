<?php

namespace App\Http\Controllers\Portal;

use App\Domains\Blog\Markdown\PostRenderer;
use App\Domains\Blog\Markdown\SummaryRenderer;
use App\Domains\Portal\Models\PortalCharacter;
use App\Domains\Portal\Models\PortalEpisode;
use App\Domains\Portal\Models\PortalSeason;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(SummaryRenderer $markdownEngine): View
    {
        $seasonsWithEpisodes = PortalSeason::query()
            ->with([
                'episodes' => fn (HasMany $query) => $query->orderBy('episode_number'),
            ])
            ->orderBy('season_number')
            ->get();

        return view('portal.index', [
            'episodesBySeason' => $seasonsWithEpisodes,
            'toMarkdown' => $markdownEngine,
        ]);
    }

    public function episode(SummaryRenderer $summaryMarkdown, PostRenderer $postMarkdown, int $seasonNum, int $episodeNum): View
    {
        $episode = PortalEpisode::query()
            ->with([
                'season',
                'characters',
            ])
            ->whereRelation('season', 'season_number', $seasonNum)
            ->whereEpisodeNumber($episodeNum)
            ->firstOrFail();

        return view('portal.episode', [
            'episode' => $episode,
            'toSummaryMarkdown' => $summaryMarkdown,
            'toPostMarkdown' => $postMarkdown,
        ]);
    }

    public function character(SummaryRenderer $summaryMarkdown, PostRenderer $postMarkdown, string $slug): View
    {
        $character = PortalCharacter::query()
            ->with([
                'episodes' => fn (BelongsToMany $query) => $query->orderBy('episode_number'),
                'episodes.season',
            ])
            ->whereSlug($slug)
            ->firstOrFail();

        return view('portal.character', [
            'character' => $character,
            'toSummaryMarkdown' => $summaryMarkdown,
            'toPostMarkdown' => $postMarkdown,
        ]);
    }
}
