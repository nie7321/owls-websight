<?php

namespace App\Domains\OpenGraph\Actions;

use App\Domains\OpenGraph\Enums\AllowedOpenGraphImageType;
use App\Domains\OpenGraph\Exceptions\UnsupportedImageException;
use App\Domains\OpenGraph\OpenGraphSpider;
use enshrined\svgSanitize\Sanitizer as SvgCleaner;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CacheOpenGraphImage
{
    public function __construct(
        protected SvgCleaner $svgCleaner,
    )
    {
        $this->svgCleaner->removeRemoteReferences(true);
        $this->svgCleaner->minify(true);
    }

    public function __invoke(string $imageUrl): ?string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'og-image_');
        $response = Http::sink($tempFile)
            ->retry(times: 3, sleepMilliseconds: 250)
            ->withUserAgent(OpenGraphSpider::USER_AGENT)
            ->get($imageUrl);

        if (! $response->successful()) {
            Log::warning('Could not download OpenGraph image', [
                'url' => $imageUrl,
                'status' => $response->status(),
                'exception' => $response->toException(),
            ]);

            return null;
        }

        $contentType = mime_content_type($tempFile);
        if (! $contentType) {
            return null;
        }

        try {
            $fileExtension = $this->contentTypeToExtention($contentType);
        } catch (UnsupportedImageException) {
            return null;
        }

        $tempFile = $this->cleanImageFactory($contentType, $tempFile);

        $dir = 'opengraph';
        $fileHash = hash_file('sha256', $tempFile);
        $publicFilename = Str::of($fileHash)->append('.', $fileExtension);
        Storage::disk('public')->putFileAs($dir, $tempFile, $publicFilename);

        return "{$dir}/{$publicFilename}";
    }

    private function contentTypeToExtention(string $contentType): string
    {
        $enum = AllowedOpenGraphImageType::tryFrom($contentType);

        return match ($enum) {
            AllowedOpenGraphImageType::JPEG, AllowedOpenGraphImageType::P_JPEG => 'jpeg',
            AllowedOpenGraphImageType::WEBP => 'webp',
            AllowedOpenGraphImageType::GIF => 'gif',
            AllowedOpenGraphImageType::PNG => 'png',
            AllowedOpenGraphImageType::APNG => 'apng',
            AllowedOpenGraphImageType::SVG => 'svg',
            default => throw UnsupportedImageException::forContentType($contentType),
        };
    }

    private function cleanImageFactory(string $contentType, string $imageFilePath): string
    {
        return match ($contentType) {
            'image/svg+xml' => $this->cleanSvg($imageFilePath),
            default => $imageFilePath,
        };
    }

    private function cleanSvg($imageFilePath): string
    {
        $impureSvgData = file_get_contents($imageFilePath);

        $pureSvgData = $this->svgCleaner->sanitize($impureSvgData);
        $cleanedPath = tempnam(sys_get_temp_dir(), 'og-image_');
        file_put_contents($cleanedPath, $pureSvgData);

        return $cleanedPath;
    }
}
