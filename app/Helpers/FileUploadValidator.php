<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileUploadValidator
{
    /**
     * Allowed file types for papers
     */
    public const PAPER_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    /**
     * Allowed file types for images
     */
    public const IMAGE_TYPES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Allowed file types for payment proof
     */
    public const PAYMENT_TYPES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'application/pdf',
    ];

    /**
     * Maximum file sizes (in KB)
     */
    public const MAX_PAPER_SIZE = 10240; // 10MB
    public const MAX_IMAGE_SIZE = 5120;  // 5MB
    public const MAX_PAYMENT_SIZE = 2048; // 2MB

    /**
     * Validate paper file upload
     */
    public static function validatePaper(UploadedFile $file): array
    {
        return self::validate($file, self::PAPER_TYPES, self::MAX_PAPER_SIZE, 'Paper');
    }

    /**
     * Validate image file upload
     */
    public static function validateImage(UploadedFile $file): array
    {
        return self::validate($file, self::IMAGE_TYPES, self::MAX_IMAGE_SIZE, 'Image');
    }

    /**
     * Validate payment proof file upload
     */
    public static function validatePayment(UploadedFile $file): array
    {
        return self::validate($file, self::PAYMENT_TYPES, self::MAX_PAYMENT_SIZE, 'Payment');
    }

    /**
     * General file validation
     */
    private static function validate(UploadedFile $file, array $allowedTypes, int $maxSize, string $fileType): array
    {
        $errors = [];

        // Check file size
        if ($file->getSize() > $maxSize * 1024) {
            $errors[] = "{$fileType} size must not exceed " . ($maxSize / 1024) . "MB";
        }

        // Check mime type
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            $errors[] = "{$fileType} type is not allowed. Allowed types: " . implode(', ', self::getExtensions($allowedTypes));
        }

        // Check if file is valid
        if (!$file->isValid()) {
            $errors[] = "{$fileType} upload failed or corrupted";
        }

        // Security check: validate file extension matches mime type
        $extension = $file->getClientOriginalExtension();
        if (!self::isExtensionMatchMime($extension, $file->getMimeType())) {
            $errors[] = "{$fileType} extension does not match file type";
        }

        // Check for executable files (security)
        if (self::isExecutable($file)) {
            $errors[] = "Executable files are not allowed";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Get file extensions from mime types
     */
    private static function getExtensions(array $mimeTypes): array
    {
        $extensions = [];
        $mimeMap = [
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'image/jpeg' => 'jpeg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        foreach ($mimeTypes as $mime) {
            if (isset($mimeMap[$mime])) {
                $extensions[] = $mimeMap[$mime];
            }
        }

        return $extensions;
    }

    /**
     * Check if extension matches mime type
     */
    private static function isExtensionMatchMime(string $extension, string $mimeType): bool
    {
        $validCombinations = [
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'jpg' => ['image/jpeg', 'image/jpg'],
            'jpeg' => ['image/jpeg', 'image/jpg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'webp' => ['image/webp'],
        ];

        $extension = strtolower($extension);
        
        if (!isset($validCombinations[$extension])) {
            return false;
        }

        return in_array($mimeType, $validCombinations[$extension]);
    }

    /**
     * Check if file is executable (security check)
     */
    private static function isExecutable(UploadedFile $file): bool
    {
        $dangerousExtensions = [
            'exe', 'bat', 'cmd', 'sh', 'php', 'php3', 'php4', 'php5', 'phtml',
            'py', 'rb', 'pl', 'cgi', 'jsp', 'asp', 'aspx', 'jar', 'war',
            'com', 'scr', 'vbs', 'js', 'app', 'deb', 'rpm'
        ];

        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, $dangerousExtensions)) {
            return true;
        }

        // Check file content for PHP tags (basic check)
        if ($file->getMimeType() === 'text/plain' || str_contains($file->getMimeType(), 'text/')) {
            $content = file_get_contents($file->getRealPath());
            if (str_contains($content, '<?php') || str_contains($content, '<?=')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sanitize filename
     */
    public static function sanitizeFilename(string $filename): string
    {
        // Remove path separators
        $filename = str_replace(['/', '\\'], '', $filename);
        
        // Remove special characters except dots, dashes, underscores
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Remove multiple dots (prevent directory traversal)
        $filename = preg_replace('/\.{2,}/', '.', $filename);
        
        // Limit length
        if (strlen($filename) > 255) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $name = substr(pathinfo($filename, PATHINFO_FILENAME), 0, 250);
            $filename = $name . '.' . $extension;
        }

        return $filename;
    }

    /**
     * Generate secure filename
     */
    public static function generateSecureFilename(UploadedFile $file, string $prefix = ''): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);
        
        $filename = $prefix 
            ? "{$prefix}_{$timestamp}_{$random}.{$extension}"
            : "{$timestamp}_{$random}.{$extension}";

        return self::sanitizeFilename($filename);
    }
}
