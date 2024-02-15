<?php

namespace Domains\Media\Actions;

use App\Domains\Media\Actions\Exif;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->withoutMetadata = base_path('tests/Unit/Domains/Media/Actions/fixtures/filetype-without-metadata.png');
    $this->withMetadata = base_path('tests/Unit/Domains/Media/Actions/fixtures/with-gps.jpg');;
    $this->exifTool = resolve(Exif::class);

    $this->copy = function (string $filePath): string {
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'jpg');

        $handle = fopen($tmpFilePath, "w");
        fwrite($handle, file_get_contents($filePath));
        fclose($handle);

        return $tmpFilePath;
    };
});

test('detects relevant images', function () {
    expect($this->exifTool->hasMetadata($this->withoutMetadata))->toBeFalse();
    expect($this->exifTool->hasMetadata($this->withMetadata))->toBeTrue();
});

test('strips metadata from jpg', function () {
    $tmpFile = ($this->copy)($this->withMetadata);
    $originalHash = File::hash($tmpFile);

    $this->exifTool->stripMetadata($tmpFile);

    expect($originalHash)->not()->toEqual(File::hash($tmpFile));

    $exifOriginal = exif_read_data($this->withMetadata);
    expect($exifOriginal)->toHaveKey('GPSLatitude');

    $exifStripped = exif_read_data($tmpFile);
    expect($exifStripped)->not()->toHaveKey('GPSLatitude');
});

test('noop when stripping non-jpeg', function () {
    $tmpFile = ($this->copy)($this->withoutMetadata);
    $originalHash = File::hash($tmpFile);

    $this->exifTool->stripMetadata($tmpFile);

    expect($originalHash)->toEqual(File::hash($tmpFile));
});
