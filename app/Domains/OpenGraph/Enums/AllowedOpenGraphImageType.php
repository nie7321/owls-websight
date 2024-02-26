<?php

namespace App\Domains\OpenGraph\Enums;

enum AllowedOpenGraphImageType: string
{
    case JPEG = 'image/jpeg';
    case P_JPEG = 'image/pjpeg';
    case WEBP = 'image/webp';
    case GIF = 'image/gif';
    case PNG = 'image/png';
    case APNG = 'image/apng';
    case SVG = 'image/svg+xml';
}
