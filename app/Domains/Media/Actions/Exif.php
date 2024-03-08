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
        // Colour profiles are important to preserve
        $profiles = $image->getImageProfiles('icc', true);

        /**
         * Need to physically rotate the image, because stripping the exif metadata gets rid of the Orientation property,
         * and imagick does not seem to be capable of putting it back? The {@see Imagick::setImageOrientation} does
         * nothing, even if I add an empty exif profile back.
         */
        $image = $this->rotateImage($image, $image->getImageOrientation());
        $image->stripImage();

        if(! empty($profiles)) {
            $image->profileImage('icc', $profiles['icc']);
        }

        return $image;
    }

    /**
     * Taken from Intervention
     *
     * @see https://github.com/Intervention/image/blob/5cd2641a99b822a6abc86f4bb03d0b5a3afa0c0e/src/Drivers/Imagick/Modifiers/AlignRotationModifier.php#L12
     */
    private function rotateImage(Imagick $image, int $imagickOrientationValue): Imagick
    {
        switch ($imagickOrientationValue) {
            case Imagick::ORIENTATION_TOPRIGHT: // 2
                $image->flopImage();
                break;

            case Imagick::ORIENTATION_BOTTOMRIGHT: // 3
                $image->rotateImage('#000', 180);
                break;

            case Imagick::ORIENTATION_BOTTOMLEFT: // 4
                $image->rotateimage("#000", 180);
                $image->flopImage();
                break;

            case Imagick::ORIENTATION_LEFTTOP: // 5
                $image->rotateimage("#000", -270);
                $image->flopImage();
                break;

            case Imagick::ORIENTATION_RIGHTTOP: // 6
                $image->rotateimage("#000", -270);
                break;

            case Imagick::ORIENTATION_RIGHTBOTTOM: // 7
                $image->rotateimage("#000", -90);
                $image->flopImage();
                break;

            case Imagick::ORIENTATION_LEFTBOTTOM: // 8
                $image->rotateimage("#000", -90);
                break;
        }

        return $image;
    }
}
