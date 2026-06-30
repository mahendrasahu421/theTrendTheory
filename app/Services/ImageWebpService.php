<?php
// app/Services/ImageWebpService.php
// Pure PHP GD se image → WebP convert + compress
// Koi extra package install nahi karna

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageWebpService
{
    // Quality: 0-100 (80 = best balance of size/quality)
    private int $quality;
    // Max dimension (width ya height) — aspect ratio maintain hogi
    private int $maxWidth;
    private int $maxHeight;

    public function __construct(int $quality = 80, int $maxWidth = 1200, int $maxHeight = 1200)
    {
        $this->quality   = $quality;
        $this->maxWidth  = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    /**
     * UploadedFile → WebP compress → temp path return karo
     * Phir ImageKit ya storage pe upload kar sako
     */
    public function convertToWebp(UploadedFile $file): string
    {
        if (!extension_loaded('gd')) {
            throw new \RuntimeException('GD extension is not enabled. php.ini mein extension=gd uncomment karo.');
        }

        $mime = $file->getMimeType();
        $sourcePath = $file->getRealPath();

        // Source image create karo
        $sourceImage = match(true) {
            str_contains($mime, 'jpeg') || str_contains($mime, 'jpg') => imagecreatefromjpeg($sourcePath),
            str_contains($mime, 'png')  => imagecreatefrompng($sourcePath),
            str_contains($mime, 'webp') => imagecreatefromwebp($sourcePath),
            str_contains($mime, 'gif')  => imagecreatefromgif($sourcePath),
            str_contains($mime, 'bmp')  => imagecreatefrombmp($sourcePath),
            default => throw new \InvalidArgumentException("Unsupported image type: {$mime}")
        };

        if (!$sourceImage) {
            throw new \RuntimeException('Image load nahi ho sakti. File corrupt ho sakti hai.');
        }

        // Original dimensions
        $origW = imagesx($sourceImage);
        $origH = imagesy($sourceImage);

        // New dimensions calculate karo (aspect ratio maintain)
        [$newW, $newH] = $this->calcDimensions($origW, $origH);

        // Resize karo
        $resized = imagecreatetruecolor($newW, $newH);

        // PNG/WebP ke liye transparency preserve karo
        if (str_contains($mime, 'png') || str_contains($mime, 'webp')) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefilledrectangle($resized, 0, 0, $newW, $newH, $transparent);
        }

        // High quality resize
        imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($sourceImage);

        // Temp file banao .webp extension ke saath
        $tempPath = sys_get_temp_dir() . '/' . Str::random(16) . '.webp';

        // WebP mein save karo
        $success = imagewebp($resized, $tempPath, $this->quality);
        imagedestroy($resized);

        if (!$success || !file_exists($tempPath)) {
            throw new \RuntimeException('WebP conversion failed. Server pe imagewebp() support check karo.');
        }

        return $tempPath;
    }

    /**
     * WebP file ko UploadedFile jaisa object banao
     * (ImageKitService ko pass karne ke liye)
     */
    public function makeTempUploadedFile(string $tempPath, string $originalName): UploadedFile
    {
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $newName  = Str::slug($baseName) . '_' . time() . '.webp';

        return new UploadedFile(
            $tempPath,
            $newName,
            'image/webp',
            null,
            true  // test mode = true (temp file hai, move allowed)
        );
    }

    /**
     * Convert + cleanup helper
     * Returns: ['path' => tempPath, 'file' => UploadedFile]
     */
    public function process(UploadedFile $file): array
    {
        $tempPath    = $this->convertToWebp($file);
        $uploadedFile = $this->makeTempUploadedFile($tempPath, $file->getClientOriginalName());

        return [
            'temp_path' => $tempPath,
            'file'      => $uploadedFile,
            'size'      => filesize($tempPath),
            'size_kb'   => round(filesize($tempPath) / 1024, 1),
        ];
    }

    /**
     * Temp file cleanup (upload ke baad call karo)
     */
    public function cleanup(string $tempPath): void
    {
        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }
    }

    /**
     * Max dimensions ke andar fit karo (aspect ratio maintain)
     */
    private function calcDimensions(int $w, int $h): array
    {
        if ($w <= $this->maxWidth && $h <= $this->maxHeight) {
            return [$w, $h]; // Already small enough
        }

        $ratioW = $this->maxWidth  / $w;
        $ratioH = $this->maxHeight / $h;
        $ratio  = min($ratioW, $ratioH);

        return [
            (int) round($w * $ratio),
            (int) round($h * $ratio),
        ];
    }
}