<?php

namespace App\Domains\Blog\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Footnote\FootnoteExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;

/**
 * The {@see TableOfContentsExtension} and {@see HeadingPermalinkExtension} cannot be used here due to restrictions
 * in what HTML is permitted for items.
 */
class AtomPostRenderer extends AbstractRenderer
{
    protected function configuration(): Environment
    {
        $env = new Environment([]);

        $env->addExtension(new CommonMarkCoreExtension);
        $env->addExtension(new GithubFlavoredMarkdownExtension);
        $env->addExtension(new FootnoteExtension);

        return $env;
    }
}
