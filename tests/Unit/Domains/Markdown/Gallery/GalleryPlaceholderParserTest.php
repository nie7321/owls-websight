<?php

namespace Domains\Markdown\Gallery;

use App\Domains\Markdown\Gallery\GalleryExtension;
use App\Domains\Markdown\Gallery\GalleryPlaceholder;
use Illuminate\Support\Collection;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Parser\MarkdownParser;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GalleryPlaceholderParserTest extends TestCase
{
    public static function markdownProvider(): array
    {
        return [
            'not present' => ['foo bar baz', []],
            'bad slug' => ['{{ dog-foo BAR }}', []],
            'correct usage' => ['{{ foo-bar }}', ['foo-bar']],
            'multiple galleries' => ["this is a blog post\n\n{{ foo-bar }}\n\nmore text\n\nmore text\n\n{{ second-gallery }}", ['foo-bar', 'second-gallery']],
            'should not detect it under other blocks' => ["```js\n\nconst foo = 'bar';\n\n{{ bad-one }}\n\n```\n\nfoo foo\n\n{{ good-one }}\n", ['good-one']],
            'must be the only thing on the line' => ["this is a blog {{ foo-bar }}post", []],
            'weird spacing OK' => ["{{foo}}\n\n{{ bar}}\n\n{{baz }}\n\n{{   bat    }}", ['foo', 'bar', 'baz', 'bat']],
        ];
    }

    #[DataProvider('markdownProvider')]
    public function testParser(string $markdown, array $detectedSlugs): void
    {
        $foundGallerySlugs = $this->parse($markdown);

        $this->assertEquals($detectedSlugs, $foundGallerySlugs->map->slug->all());
    }

    /**
     * @return Collection<GalleryPlaceholder>
     */
    private function parse(string $markdown): Collection
    {
        $env = new Environment();
        $env->addExtension(new CommonMarkCoreExtension);
        $env->addExtension(new GalleryExtension);

        $parser = new MarkdownParser($env);

        $astWalker = $parser->parse($markdown)->walker();

        $galleryPlaceholderNodes = [];
        while ($walkEvent = $astWalker->next()) {
            if ($walkEvent->getNode() instanceof GalleryPlaceholder && $walkEvent->isEntering()) {
                $galleryPlaceholderNodes[] = $walkEvent->getNode();
            }
        }

        return collect($galleryPlaceholderNodes);
    }
}
