<?php

namespace App\View\Components;

use App\Domains\Media\Models\Image;
use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?Image $previewImage = null,
    )
    {
        //
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
