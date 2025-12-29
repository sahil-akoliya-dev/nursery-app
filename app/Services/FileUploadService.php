<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileUploadService
{
    /**
     * Allowed MIME types for images
     */
    private const ALLOWED_IMAGE_MIMES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Maximum file size in bytes (5MB)
     */
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

    /**
     * Maximum number of images
     */
    private const MAX_IMAGES = 5;

    /**
     * Upload and process image with security checks
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $options
     * @return string Path to stored file
     * @throws \Exception
     */
    public function uploadImage(UploadedFile $file, string $directory = 'uploads', array $options = []): string
    {
        // Validate file type
        $this->validateImage($file);

        // Generate secure filename
        $filename = $this->generateSecureFilename($file);

        // Process image if needed
        if (isset($options['resize']) && $options['resize']) {
            return $this->uploadAndResizeImage($file, $directory, $filename, $options);
        }

        // Store original file
        $path = $file->storeAs($directory, $filename, 'public');

        return Storage::url($path);
    }

    /**
     * Upload multiple images
     *
     * @param array $files
     * @param string $directory
     * @param array $options
     * @return array Array of file paths
     * @throws \Exception
     */
    public function uploadImages(array $files, string $directory = 'uploads', array $options = []): array
    {
        if (count($files) > self::MAX_IMAGES) {
            throw new \Exception('Maximum ' . self::MAX_IMAGES . ' images allowed.');
        }

        $uploadedFiles = [];

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            try {
                $uploadedFiles[] = $this->uploadImage($file, $directory, $options);
            } catch (\Exception $e) {
                // Log error but continue with other files
                \Log::error('File upload failed: ' . $e->getMessage());
            }
        }

        return $uploadedFiles;
    }

    /**
     * Validate image file
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    private function validateImage(UploadedFile $file): void
    {
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('File size exceeds maximum allowed size of ' . (self::MAX_FILE_SIZE / 1024 / 1024) . 'MB.');
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, self::ALLOWED_IMAGE_MIMES, true)) {
            throw new \Exception('Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.');
        }

        // Verify file extension matches MIME type
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $allowedExtensions, true)) {
            throw new \Exception('Invalid file extension.');
        }

        // Check if file is actually an image by reading first bytes
        $this->validateImageContent($file);
    }

    /**
     * Validate image content by reading file header
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    private function validateImageContent(UploadedFile $file): void
    {
        $handle = fopen($file->getRealPath(), 'rb');
        if (!$handle) {
            throw new \Exception('Unable to read file.');
        }

        $header = fread($handle, 12);
        fclose($handle);

        // Check image file signatures (magic numbers)
        $signatures = [
            'image/jpeg' => ["\xFF\xD8\xFF"],
            'image/png' => ["\x89\x50\x4E\x47\x0D\x0A\x1A\x0A"],
            'image/gif' => ["GIF87a", "GIF89a"],
            'image/webp' => ["RIFF"], // WebP starts with RIFF, then checks for WEBP
        ];

        $mimeType = $file->getMimeType();
        $valid = false;

        if (isset($signatures[$mimeType])) {
            foreach ($signatures[$mimeType] as $signature) {
                if (strpos($header, $signature) === 0) {
                    $valid = true;
                    break;
                }
            }
        }

        // Additional WebP validation
        if ($mimeType === 'image/webp' && strpos($header, 'RIFF') === 0) {
            $handle = fopen($file->getRealPath(), 'rb');
            fseek($handle, 8);
            $webpHeader = fread($handle, 4);
            fclose($handle);
            if ($webpHeader === 'WEBP') {
                $valid = true;
            }
        }

        if (!$valid) {
            throw new \Exception('File content does not match declared file type.');
        }
    }

    /**
     * Generate secure filename
     *
     * @param UploadedFile $file
     * @return string
     */
    private function generateSecureFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $timestamp = time();
        $random = Str::random(16);

        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Upload and resize image
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $filename
     * @param array $options
     * @return string
     */
    private function uploadAndResizeImage(UploadedFile $file, string $directory, string $filename, array $options): string
    {
        $maxWidth = $options['max_width'] ?? 1200;
        $maxHeight = $options['max_height'] ?? 1200;
        $quality = $options['quality'] ?? 85;

        try {
            $image = Image::make($file->getRealPath());

            // Resize maintaining aspect ratio
            $image->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Optimize quality
            if (in_array($file->getMimeType(), ['image/jpeg', 'image/jpg'], true)) {
                $image->encode('jpg', $quality);
            } elseif ($file->getMimeType() === 'image/png') {
                $image->encode('png', round($quality / 10)); // PNG uses 0-9 scale
            }

            // Store processed image
            $fullPath = storage_path('app/public/' . $directory . '/' . $filename);
            $directoryPath = dirname($fullPath);
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }

            $image->save($fullPath);

            return Storage::url($directory . '/' . $filename);
        } catch (\Exception $e) {
            \Log::error('Image processing failed: ' . $e->getMessage());
            throw new \Exception('Failed to process image.');
        }
    }

    /**
     * Delete file
     *
     * @param string $path
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        // Remove /storage/ prefix if present
        $path = str_replace('/storage/', '', parse_url($path, PHP_URL_PATH));
        return Storage::disk('public')->delete($path);
    }

    /**
     * Delete multiple files
     *
     * @param array $paths
     * @return void
     */
    public function deleteFiles(array $paths): void
    {
        foreach ($paths as $path) {
            $this->deleteFile($path);
        }
    }
}

