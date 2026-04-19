<?php

namespace App\Http\Controllers\Portal;

use App\Domains\Portal\Models\PortalCharacter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(): View
    {
        return view('portal.index');
    }

    public function episode(int $season, int $episode): View
    {


        return view('portal.episode');
    }

    public function character(string $character): View
    {
        $character = PortalCharacter::whereSlug($character)->firstOrFail();

        return view('portal.character', [
            'character' => $character,
        ]);
    }
}
