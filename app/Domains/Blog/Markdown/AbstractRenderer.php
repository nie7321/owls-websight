<?php

namespace App\Domains\Blog\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\MarkdownConverter;

abstract class AbstractRenderer
{
    protected MarkdownConverter $converter;

    public function __construct()
    {
        $this->converter = new MarkdownConverter($this->configuration());
    }

    public function convert(string $markdown): string
    {
        return $this->converter->convert($markdown)->getContent();
    }

    abstract protected function configuration(): Environment;
}
