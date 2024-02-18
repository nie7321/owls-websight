<?php

namespace App\Domains\Blog\Markdown;

use App\Domains\Markdown\Gallery\GalleryExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Footnote\FootnoteExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;

class PostRenderer extends AbstractRenderer
{
    protected function configuration(): Environment
    {
        $env = new Environment([
            'table_of_contents' => [
                'position' => 'placeholder',
                'placeholder' => '[TOC]',
            ],
            'heading_permalink' => [
                // Need the plugin for the ToC to work, but I don't want the link icon showing up.
                'symbol' => '',
            ],
        ]);

        $env->addExtension(new CommonMarkCoreExtension);
        $env->addExtension(new GithubFlavoredMarkdownExtension);
        $env->addExtension(new FootnoteExtension);
        $env->addExtension(new HeadingPermalinkExtension);
        $env->addExtension(new TableOfContentsExtension);
        $env->addExtension(new GalleryExtension);

        return $env;
    }
}
