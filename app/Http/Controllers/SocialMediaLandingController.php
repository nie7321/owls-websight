<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SocialMediaLandingController extends Controller
{
    public function __invoke(Request $request)
    {
        return redirect(config('social.redirect'));
    }
}
