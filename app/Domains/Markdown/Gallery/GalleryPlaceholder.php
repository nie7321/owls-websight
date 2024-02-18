<?php

namespace App\Domains\Markdown\Gallery;

use League\CommonMark\Node\Block\AbstractBlock;

class GalleryPlaceholder extends AbstractBlock
{
    public function __construct(
        public readonly string $slug,
    )
    {
        parent::__construct();
    }
}
