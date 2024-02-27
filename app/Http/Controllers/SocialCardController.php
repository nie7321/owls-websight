<?php

namespace App\Http\Controllers;

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

        $title = $data['title'];
        $seed = crc32($title);

        // BG, text fill, text stroke
        $colourPairs = collect([
            ['#FFFFFF', '#333333', '#000000'],
            ['#F0F0F0', '#444444', '#000000'],
            ['#FFCC66', '#333333', '#000000'],
            ['#6699CC', '#FFFFFF', '#000000'],
            ['#FF6699', '#FFFFFF', '#000000'],
            ['#CC99FF', '#333333', '#000000'],
            ['#99CC99', '#333333', '#000000'],
            ['#FF9966', '#FFFFFF', '#000000'],
            ['#CCCCFF', '#333333', '#000000'],
            ['#66CCCC', '#FFFFFF', '#000000'],
            ['#FFFF99', '#333333', '#000000'],
            ['#FF99CC', '#FFFFFF', '#000000'],
            ['#CCFF66', '#333333', '#000000'],
            ['#FFCC99', '#333333', '#000000'],
            ['#99CCCC', '#333333', '#000000'],
            ['#FFCCCC', '#333333', '#000000'],
            ['#CCCC66', '#333333', '#000000'],
            ['#CCFFCC', '#333333', '#000000'],
            ['#FF99FF', '#333333', '#000000'],
            ['#99FFCC', '#333333', '#000000'],
        ]);

        [$bgColour, $textFillColour, $textOutlineColour] = $colourPairs->shuffle($seed)->first();

        $svg = view('social-card.card', [
            'title' => $title,
            'textFill' => $textFillColour,
            'textStroke' => $textOutlineColour,
            'bgColour' => $bgColour,
        ])->render();

        return response($svgCleaner->sanitize($svg))
            ->header('Content-Type', 'image/svg+xml');
    }
}
