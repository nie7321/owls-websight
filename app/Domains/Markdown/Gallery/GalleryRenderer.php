<?php

namespace App\Domains\Markdown\Gallery;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Xml\XmlNodeRendererInterface;

class GalleryRenderer implements NodeRendererInterface, XmlNodeRendererInterface
{
    /**
     * @param GalleryPlaceholder $node
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        GalleryPlaceholder::assertInstanceOf($node);

        /**
         * - Validate the gallery slug
         *  - Do something if it doesn't exist
         * - Eager load everything
         * Render the images!!!!!
         */
        
        return "<div style='color: pink;'>gallery {$node->slug} goes here</div>";
    }

    public function getXmlTagName(Node $node): string
    {
        return 'gallery';
    }

    public function getXmlAttributes(Node $node): array
    {
        return [];
    }
}
