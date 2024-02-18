<?php

namespace App\Domains\Markdown\Gallery;

use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;
use League\CommonMark\Util\RegexHelper;

class GalleryPlaceholderParser extends AbstractBlockContinueParser
{
    public function __construct(
        private GalleryPlaceholder $block,
    )
    {
        //
    }

    public function getBlock(): GalleryPlaceholder
    {
        return $this->block;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        return BlockContinue::none();
    }

    public static function blockStartParser(): BlockStartParserInterface
    {
        return new class () implements BlockStartParserInterface {
            public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
            {
                // The tag must be the only thing on the line
                $match = RegexHelper::matchFirst('/^\s*{{(\s*[A-Z0-9_-]+\s*)}}\s*$/i', $cursor->getLine());
                if ($match === null) {
                    return BlockStart::none();
                }

                $block = new GalleryPlaceholder(trim($match[1]));
                return BlockStart::of(new GalleryPlaceholderParser($block))->at($cursor);
            }
        };
    }
}
