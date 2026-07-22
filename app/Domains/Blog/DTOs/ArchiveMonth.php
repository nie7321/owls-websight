<?php

declare(strict_types=1);

namespace App\Domains\Blog\DTOs;

use Carbon\Carbon;
use Carbon\Month;

readonly class ArchiveMonth
{
    public function __construct(
        public int $year,
        public int $month,
        public int $postCount
    ) {
        //
    }

    public function monthLabel(): string
    {
        return Month::from($this->month)->name;
    }

    public function padMonth(): string
    {
        return str_pad((string) $this->month, 2, '0', pad_type: STR_PAD_LEFT);
    }
}
