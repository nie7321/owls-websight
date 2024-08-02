<?php

declare(strict_types=1);

namespace App\Domains\Opml\Actions;

use App\Domains\Opml\Exceptions\OpmlOutlineMissing;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class CanonicalizeFeedUrl
{
    /**
     * @param string $url URL for an OPML file
     * @return SimpleXMLElement Updated OPML. Call {@see SimpleXMLElement::asXML()} to get the XML.
     */
    public function forOpml(string $url): SimpleXMLElement
    {
        $opml = $this->getOpml($url);
        if (! $opml->body?->outline) {
            throw new OpmlOutlineMissing;
        }

        foreach ($opml->body->outline->children() as $outline) {
            $cannonicalUrl = $this->getCannonicalFeedUrl((string) $outline['xmlUrl']);
            if (! $cannonicalUrl) {
                // If this failed (site down or w/e), then ... do nothing?
                continue;
            }

            $outline['xmlUrl'] = $cannonicalUrl;
        }

        return $opml;
    }

    private function getOpml(string $url): SimpleXMLElement
    {
        $resp = Http::get($url);
        if (! $resp->successful()) {
            $resp->throw();
        }

        // Any & in URLs will cause the XML parsers to choke.
        $cleanedOpml = preg_replace("|&([^;]+?)[\s<&]|","&#038;$1 ",$resp->body());

        return simplexml_load_string($cleanedOpml);
    }

    private function getCannonicalFeedUrl(string $url): ?string
    {
        try {
            $resp = Http::withOptions(['allow_redirects' => false])->get($url);

            if ($resp->redirect()) {
                return $this->getCannonicalFeedUrl($resp->header('Location'));
            }

            if ($resp->successful()) {
                return $url;
            }
        } catch (ConnectionException $e) {
            // do nothing, let the null below handle it.
        }

        return null;
    }

}
