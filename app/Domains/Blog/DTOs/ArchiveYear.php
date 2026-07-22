<?php

declare(strict_types=1);

namespace App\Domains\Blog\DTOs;

use Illuminate\Support\Collection;

readonly class ArchiveYear
{
    /**
     * @param Collection<ArchiveMonth> $months
     */
    public function __construct(
        public int $year,
        public Collection $months,
    )
    {
        //
    }

    public function totalPosts(): int
    {
        return $this->months->sum('postCount');
    }
}
