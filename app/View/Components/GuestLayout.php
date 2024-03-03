<?php

namespace App\View\Components;

use App\Domains\Blog\Models\LinkCategory;
use App\Domains\Media\Models\Image;
use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public readonly ?string $description;

    public function __construct(
        public readonly ?string $title = null,
        public readonly ?Image $previewImage = null,
        ?string $description = null,
    )
    {
        $this->description = str_replace("\n", " ", $description);
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest', [
            'linkCategories' => LinkCategory::all(),
        ]);
    }
}
