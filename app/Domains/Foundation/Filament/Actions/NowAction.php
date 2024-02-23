<?php

namespace App\Domains\Foundation\Filament\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Set;

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
