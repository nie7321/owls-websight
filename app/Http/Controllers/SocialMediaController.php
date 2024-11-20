<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SocialMediaController extends Controller
{
    public function index(Request $request)
    {
        return redirect(config('social.redirect'));
    }

    public function atprotoDID(): Response
    {
        return response(config('social.atproto.did'))
            ->header('Content-Type', 'plain/text');
    }
}
