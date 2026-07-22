<?php
declare(strict_types=1);

function cors(): void
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}

function json_response(array $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

function json_error(string $message, int $status): never
{
    json_response([
        'error' => [
            'message' => $message,
            'status' => $status,
        ],
    ], $status);
}

function read_request_payload(): array
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (str_contains($contentType, 'application/json')) {
        $raw = file_get_contents('php://input') ?: '{}';
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    $payload = $_POST;
    if (isset($payload['payload']) && is_string($payload['payload'])) {
        $decoded = json_decode($payload['payload'], true);
        if (is_array($decoded)) {
            $payload['payload'] = $decoded;
        }
    }

    return $payload;
}

function normalize_uploaded_files(): array
{
    if (empty($_FILES)) {
        return [];
    }

    $normalized = [];
    foreach ($_FILES as $field => $fileSet) {
        if (!is_array($fileSet['name'])) {
            $normalized[] = [
                'field' => $field,
                'name' => $fileSet['name'],
                'tmp_name' => $fileSet['tmp_name'],
                'type' => $fileSet['type'],
                'size' => $fileSet['size'],
                'error' => $fileSet['error'],
            ];
            continue;
        }

        foreach ($fileSet['name'] as $index => $name) {
            $normalized[] = [
                'field' => $field,
                'name' => $name,
                'tmp_name' => $fileSet['tmp_name'][$index],
                'type' => $fileSet['type'][$index],
                'size' => $fileSet['size'][$index],
                'error' => $fileSet['error'][$index],
            ];
        }
    }

    return array_values(array_filter($normalized, static fn (array $file): bool => (int) $file['error'] !== UPLOAD_ERR_NO_FILE));
}

function storage_path(string $file = ''): string
{
    $base = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage';
    return $file === '' ? $base : $base . DIRECTORY_SEPARATOR . $file;
}

function safe_filename(string $filename): string
{
    $filename = preg_replace('/[^A-Za-z0-9._-]+/', '-', $filename) ?: 'archivo';
    return trim($filename, '-.');
}
