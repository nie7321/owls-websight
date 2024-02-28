<?php

namespace App\Http\Controllers;

use App\Domains\OpenGraph\Actions\DefaultCardSettings;
use enshrined\svgSanitize\Sanitizer as SvgCleaner;
use Illuminate\Http\Request;

class SocialCardController extends Controller
{
    /**
     * Generates an SVG
     */
    public function __invoke(Request $request, SvgCleaner $svgCleaner)
    {
        $data = $request->validate([
            'title' => 'required|string',
        ]);

        $cardSettings = new DefaultCardSettings($data['title']);

        $svg = view('social-card.card', [
            'settings' => new DefaultCardSettings($data['title']),
        ])->render();

        return response($svgCleaner->sanitize($svg))
            ->header('Content-Type', 'image/svg+xml');
    }
}
