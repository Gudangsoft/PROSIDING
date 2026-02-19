<?php
/**
 * Storage File Server
 * Pengganti symlink saat exec() dan symlink() diblokir hosting.
 * Melayani file dari storage/app/public/ via URL /storage/...
 */

// Path ke storage/app/public relatif dari file ini (public/storage/index.php)
// public/storage/ → naik 2x → root project → storage/app/public/
$storageRoot = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public';

// Ambil path yang diminta, buang prefix /storage/
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestUri = parse_url($requestUri, PHP_URL_PATH);

// Buang /storage/ dari awal path
$requestUri = preg_replace('#^.*/storage/#', '', $requestUri);
$requestUri = ltrim($requestUri, '/');

// Cegah path traversal
if (str_contains($requestUri, '..') || str_contains($requestUri, "\0")) {
    http_response_code(403);
    exit('Forbidden');
}

$filePath = $storageRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $requestUri);

// Cek file ada
if (!file_exists($filePath) || !is_file($filePath)) {
    http_response_code(404);
    exit('File not found: ' . htmlspecialchars($requestUri));
}

// Deteksi MIME type
$mime = null;
if (function_exists('mime_content_type')) {
    $mime = mime_content_type($filePath);
}
if (!$mime) {
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimeMap = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'webp' => 'image/webp',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'pdf'  => 'application/pdf',
        'zip'  => 'application/zip',
        'mp4'  => 'video/mp4',
        'mp3'  => 'audio/mpeg',
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'json' => 'application/json',
        'txt'  => 'text/plain',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];
    $mime = $mimeMap[$ext] ?? 'application/octet-stream';
}

// Cache header
$lastModified = filemtime($filePath);
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
header('Cache-Control: public, max-age=86400');
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($filePath));

readfile($filePath);
exit;
