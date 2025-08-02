<?php

declare(strict_types=1);

namespace App\Domains\RssDiscovery\Actions;

use DOMDocument;
use Exception;

class OpmlWriter
{
    public function toXml(string $path, array $outlineNodes): string
    {
        $doc = self::opmlTemplate();

        foreach ($outlineNodes as $outlineStr) {
            $node = $doc->importNode(dom_import_simplexml(simplexml_load_string($outlineStr)));
            $doc->documentElement->getElementsByTagName('body')[0]->appendChild($node);
        }

        $xml = $doc->saveXML();
        throw_unless($xml, new Exception('XML failure'));

        $writeResult = file_put_contents($path, $xml);
        throw_unless($writeResult, new Exception('Writing file failed'));

        return $xml;
    }

    static public function opmlTemplate(): DOMDocument
    {
        $xml = <<<'XML'
        <?xml version="1.0" encoding="UTF-8"?>
        <opml version="1.0">
            <head>
                <title>Blaugust 2025 Feeds</title>
            </head>
            <body/>
        </opml>
        XML;

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXml($xml);

        return $dom;
    }
}
