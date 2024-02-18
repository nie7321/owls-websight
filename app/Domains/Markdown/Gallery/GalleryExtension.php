<?php

namespace App\Domains\Markdown\Gallery;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class GalleryExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(GalleryPlaceholder::class, new GalleryRenderer);
        $environment->addBlockStartParser(GalleryPlaceholderParser::blockStartParser(), 200);
    }
}
