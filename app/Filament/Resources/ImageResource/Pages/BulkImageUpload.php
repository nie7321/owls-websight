<?php

namespace App\Filament\Resources\ImageResource\Pages;

use App\Domains\Media\Actions\Exif;
use App\Domains\Media\Models\Image;
use App\Filament\Resources\ImageResource;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Resources\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use function Filament\Support\is_app_url;

/**
 * @property Form $form
 */
class BulkImageUpload extends Page implements HasForms
{
    use InteractsWithForms, HasUnsavedDataChangesAlert, InteractsWithFormActions;

    protected static string $resource = ImageResource::class;

    protected static string $view = 'filament.resources.image-resource.pages.bulk-image-upload';

    public array $images = [];
    public ?array $data = [];

    protected function authorizeAccess(): void
    {
        abort_unless(static::getResource()::canCreate(), 403);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                ->key('images')
                ->multiple()
                ->minFiles(1),
        ];
    }

    public function getFormActions(): array
    {
        return [
            $this->getSubmitFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSubmitFormAction(): Action
    {
        return Action::make('create')
            ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/create-record.form.actions.cancel.label'))
            ->url($this->previousUrl ?? static::getResource()::getUrl())
            ->color('gray');
    }

    public function create(): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            // Expected to be empty; file data doesn't get put in here.
            // But leaving this, in case I add more fields later.
            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            $this->callHook('beforeCreate');

            /** @var Forms\Components\SpatieMediaLibraryFileUpload $uploadComponent */
            $uploadComponent = $this->form->getComponent('images');

            /** @var callable(SpatieMediaLibraryFileUpload $component, TemporaryUploadedFile $file, ?Model $record): ?string $saveCallback ) $saveCallback */
            $saveCallback = invade($uploadComponent)->saveUploadedFileUsing;

            /** @var Exif $exifTool */
            $exifTool = resolve(Exif::class);

            /**
             * @var string $uuid
             * @var TemporaryUploadedFile $tempFile
             */
            foreach ($uploadComponent->getState() as $uuid => $tempFile) {
                $imageRecord = Image::create(['name' => $tempFile->getClientOriginalName()]);
                $uploadComponent->model($imageRecord);

                $exifTool->stripMetadata($tempFile->path());
                $saveCallback($uploadComponent, $tempFile, $imageRecord);
            }

            $this->callHook('afterCreate');
        } catch (Halt $exception) {
            return;
        }

        $this->rememberData();

        $redirectUrl = $this->getRedirectUrl();

        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    public function getFormStatePath(): ?string
    {
        return 'data';
    }
}
