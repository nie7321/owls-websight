<?php

declare(strict_types=1);

namespace App\Domains\Foundation\Filament\Actions;

use Closure;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;

/**
 * Based on `webbingbrasil/filament-copyactions`.
 *
 * @see https://github.com/webbingbrasil/filament-copyactions/blob/3.x/src/Concerns/HasCopyable.php
 */
class CopyToClipboardAction extends Action
{
    protected Closure | string | null $copyable = null;

    public static function getDefaultName(): ?string
    {
        return 'copy';
    }

    public function setUp(): void
    {
        parent::setUp();

        $this
            ->dispatch('FilamentCopyActions')
            ->successNotificationTitle(__('Copied!'))
            ->icon('heroicon-o-clipboard-document')
            ->extraAttributes(fn () => [
                'x-data' => '',
                'x-on:click' => new HtmlString(
                    'window.navigator.clipboard.writeText('.$this->getCopyable().');'
                    . (($title = $this->getSuccessNotificationTitle()) ? ' $tooltip('.Js::from($title).');' : '')
                ),
            ]);
    }

    public function action(Closure | string | null $action): static
    {
        $this->dispatch(null);
        return parent::action($action);
    }

    public function copyable(Closure | string | null $copyable): self
    {
        $this->copyable = $copyable;

        return $this;
    }

    public function getCopyable(): ?string
    {
        return JS::from($this->evaluate($this->copyable))->toHtml();
    }
}
