<?php

namespace App\Domains\Blog\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;

class SummaryRenderer extends AbstractRenderer
{
    protected function configuration(): Environment
    {
        $env = new Environment([]);

        $env->addExtension(new CommonMarkCoreExtension);
        $env->addExtension(new GithubFlavoredMarkdownExtension);

        return $env;
    }
}
