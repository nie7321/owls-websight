<?php

namespace App\Domains\Markdown\Gallery;

use App\Domains\Blog\Markdown\SummaryRenderer;
use App\Domains\Media\Models\Gallery;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Xml\XmlNodeRendererInterface;

class GalleryRenderer implements NodeRendererInterface, XmlNodeRendererInterface
{
    /**
     * @param GalleryPlaceholder $node
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        GalleryPlaceholder::assertInstanceOf($node);

        /**
         * - Validate the gallery slug
         *  - Do something if it doesn't exist
         * - Eager load everything
         * Render the images!!!!!
         */

        $gallery = Gallery::with(['images.media'])->whereSlug($node->slug)->first();
        if (! $gallery) {
            return view('blog.inline-gallery._not-found', ['slug' => $node->slug])->render();
        }

        if ($gallery->images->count() == 0) {
            return view('blog.inline-gallery._empty', ['slug' => $node->slug])->render();
        }

        // This is an atrocity.
        $captionRenderer = resolve(SummaryRenderer::class);

        return view('blog.inline-gallery._gallery')
            ->with([
                'slug' => $node->slug,
                'gallery' => $gallery,
                'captionRenderer' => $captionRenderer,
            ])
            ->render();
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
