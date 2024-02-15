<?php

namespace App\Domains\Media\Actions;

use Imagick;

class Exif
{
    /**
     * Strips everything but the ICC profiles from the EXIF metadata of relevant images.
     */
    public function stripMetadata(string $filePath): string
    {
        if (! $this->hasMetadata($filePath)) {
            return $filePath;
        }

        $img = new Imagick($filePath);
        if (! $img->valid()) {
            throw new \Exception('Image is not valid');
        }

        $img = $this->strip($img);
        $img->writeImage($filePath);
        $img->clear();
        $img->destroy();

        return $filePath;
    }

    public function hasMetadata(string $filePath): bool
    {
        $contentType = mime_content_type($filePath);
        $typesWithExifMetadata = ['image/jpeg', 'image/tiff'];

        return in_array($contentType, $typesWithExifMetadata, strict: true);
    }

    private function strip(Imagick $image): Imagick
    {
        $profiles = $image->getImageProfiles('icc', true);

        $image->stripImage();

        if(! empty($profiles)) {
            $image->profileImage('icc', $profiles['icc']);
        }

        return $image;
    }
}
