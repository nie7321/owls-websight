<?php

namespace App\Http\Controllers;

use App\Domains\Blog\DTOs\ArchiveMonth;
use App\Domains\Blog\DTOs\ArchiveYear;
use App\Domains\Blog\Markdown\SummaryRenderer;
use App\Domains\Blog\Models\BlogPost;
use Carbon\Month;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\View\View;

class BlogArchiveController extends Controller
{
    public function index(): View
    {
        $innerQuery = DB::query()
            ->from((new BlogPost)->getTable())
            ->whereNotNull('published_at')
            ->groupByRaw('EXTRACT(year FROM published_at::timestamp)')
            ->groupByRaw('EXTRACT(month FROM published_at::timestamp)')
            ->select([
                DB::raw('EXTRACT(year FROM published_at::timestamp) AS year'),
                DB::raw('EXTRACT(month FROM published_at::timestamp) AS month'),
                DB::raw('COUNT(*) AS post_count')
            ]);

        // Easier to do the ORDER BY out here.
        // It gets cranky about it with the GROUP BY in `$innerQuery`.
        $summary = DB::query()
            ->fromSub($innerQuery, 'counts')
            ->orderBy('year', 'desc')
            ->orderBy('month')
            ->get()
            ->map(function (object $item) {
                return new ArchiveMonth(
                    $item->year,
                    $item->month,
                    $item->post_count,
                );
            })
            ->mapToGroups(function (ArchiveMonth $month) {
                return [$month->year => $month];
            })
            ->map(function (Collection $months, int $year) {
                return new ArchiveYear($year, $months);
            });

        return view('blog.archive.index', [
            'summary' => $summary,
        ]);
    }

    public function show(SummaryRenderer $markdownEngine, int $year, int $month): View
    {
        $data = ValidatorFacade::make(
            data: ['year' => $year, 'month' => $month],
            rules: [
                'year' => 'required|integer|digits:4',
                'month' => 'required|integer|digits:1|min:1|max:12',
            ],
        )
        ->validate();

        $startAt = Carbon::createFromDate($data['year'], $data['month'])->firstOfMonth();
        $endAt = $startAt->copy()->endOfMonth();

        $posts = BlogPost::forDisplay()
            ->whereBetween('published_at', [$startAt, $endAt])
            ->paginate(10);

        return view('blog.archive.show', [
            'archiveYear' => $data['year'],
            'archiveMonth' => Month::from($data['month'])->name,
            'toMarkdown' => $markdownEngine,
            'posts'=> $posts,
        ]);

    }
}
