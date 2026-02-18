<?php

namespace App\Domains\Foundation\Filament\Actions;

use Filament\Actions\Action;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\DateTimePicker;

class NowAction extends Action
{
    protected function setUp(): void
    {
        $this->label('Now')
            ->icon('heroicon-o-clock')
            ->action(function (DateTimePicker $component, Set $set) {
                $set($component->getName(), now()->format($component->getFormat()));
            });
    }
}
