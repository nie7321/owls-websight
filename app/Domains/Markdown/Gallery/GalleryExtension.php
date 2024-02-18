<?php

namespace App\Domains\Markdown\Gallery;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class GalleryExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(GalleryPlaceholder::class, new GalleryRenderer);
        // $environment->addEventListener(DocumentParsedEvent::class, [new TableOfContentsBuilder(), 'onDocumentParsed'], -150);

        $environment->addBlockStartParser(GalleryPlaceholderParser::blockStartParser(), 200);
        // If a placeholder cannot be replaced with a TOC element this renderer will ensure the parser won't error out
        // $environment->addRenderer(TableOfContentsPlaceholder::class, new TableOfContentsPlaceholderRenderer());
    }
}
