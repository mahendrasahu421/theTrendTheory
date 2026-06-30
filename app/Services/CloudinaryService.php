<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CloudinaryService
{
    private string $cloudName;
    private string $apiKey;
    private string $apiSecret;

    public function __construct(
        private readonly ImageWebpService $imageWebp
    ) {
        $this->cloudName = (string) config('services.cloudinary.cloud_name');
        $this->apiKey = (string) config('services.cloudinary.api_key');
        $this->apiSecret = (string) config('services.cloudinary.api_secret');
    }

    public function upload(UploadedFile $file, string $folder = 'announcements'): array
    {
        $this->ensureConfigured();

        $timestamp = time();
        $publicId = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'-'.$timestamp;

        $webp = null;
        try {
            // Convert to WebP before upload
            $processed = $this->imageWebp->process($file);
            $webp = $processed['file']; // UploadedFile with .webp temp path

            $params = [
                'folder' => $folder,
                'public_id' => $publicId,
                'timestamp' => $timestamp,
            ];

            $response = Http::asMultipart()
                ->attach('file', fopen($webp->getRealPath(), 'r'), $webp->getClientOriginalName())
                ->post($this->apiUrl('image/upload'), [
                    'api_key' => $this->apiKey,
                    'timestamp' => (string) $timestamp,
                    'folder' => $folder,
                    'public_id' => $publicId,
                    'signature' => $this->signature($params),
                ]);

            if ($response->failed()) {
                throw new \RuntimeException('Cloudinary upload failed: '.$response->body());
            }

            $payload = $response->json();

            return [
                'url' => $payload['secure_url'] ?? $payload['url'] ?? null,
                'public_id' => $payload['public_id'] ?? $publicId,
            ];
        } finally {
            // Cleanup temp webp file
            if ($webp) {
                try {
                    $realPath = $webp->getRealPath();
                    if ($realPath && is_string($realPath)) {
                        $this->imageWebp->cleanup($realPath);
                    }
                } catch (\Throwable) {
                    // ignore cleanup failures
                }
            }
        }
    }


    public function delete(?string $publicId): bool
    {
        if (! $publicId) {
            return true;
        }

        $this->ensureConfigured();

        $timestamp = time();
        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];

        $response = Http::asForm()->post($this->apiUrl('image/destroy'), [
            'api_key' => $this->apiKey,
            'public_id' => $publicId,
            'timestamp' => $timestamp,
            'signature' => $this->signature($params),
        ]);

        return $response->successful();
    }

    private function apiUrl(string $action): string
    {
        return "https://api.cloudinary.com/v1_1/{$this->cloudName}/{$action}";
    }

    private function signature(array $params): string
    {
        ksort($params);

        $payload = collect($params)
            ->map(fn ($value, $key) => "{$key}={$value}")
            ->implode('&');

        return sha1($payload.$this->apiSecret);
    }

    private function ensureConfigured(): void
    {
        if (! $this->cloudName || ! $this->apiKey || ! $this->apiSecret) {
            throw new \RuntimeException('Cloudinary is not configured. Add CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, and CLOUDINARY_API_SECRET to .env.');
        }
    }
}
