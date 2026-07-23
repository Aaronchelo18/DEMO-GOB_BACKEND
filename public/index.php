<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/data.php';
require_once __DIR__ . '/../src/http.php';

cors();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/') ?: '/';

try {
    if ($method === 'GET' && $path === '/api/health') {
        json_response([
            'status' => 'ok',
            'service' => 'demo-gob-backend',
            'time' => date(DATE_ATOM),
        ]);
    }

    if ($method === 'GET' && $path === '/api/session') {
        json_response([
            'establishment' => siscat_establishment(),
            'establishments' => siscat_establishments(),
            'categories' => siscat_categories(),
            'user' => siscat_user(),
            'modules' => siscat_modules(),
        ]);
    }

    if ($method === 'GET' && $path === '/api/catalog') {
        json_response([
            'establishment' => siscat_establishment(),
            'establishments' => siscat_establishments(),
            'categories' => siscat_categories(),
            'modules' => siscat_modules(),
            'upss' => siscat_upss(),
            'services' => siscat_services(),
        ]);
    }

    if ($method === 'GET' && $path === '/api/services') {
        json_response(['services' => siscat_services()]);
    }

    if ($method === 'GET' && preg_match('#^/api/services/([^/]+)/groups/([^/]+)/items$#', $path, $matches)) {
        $items = service_group_items(rawurldecode($matches[1]), rawurldecode($matches[2]));

        if ($items === null) {
            json_error('Servicio o grupo no encontrado.', 404);
        }

        json_response(['items' => $items]);
    }

    if ($method === 'GET' && preg_match('#^/api/items/([^/]+)$#', $path, $matches)) {
        $item = find_item(rawurldecode($matches[1]));

        if ($item === null) {
            json_error('Item no encontrado.', 404);
        }

        json_response(['item' => $item]);
    }

    if ($method === 'GET' && $path === '/api/captures') {
        json_response(['captures' => read_captures()]);
    }

    if ($method === 'DELETE' && preg_match('#^/api/captures/([^/]+)$#', $path, $matches)) {
        json_response(['capture' => delete_capture(rawurldecode($matches[1]))]);
    }

    if ($method === 'GET' && preg_match('#^/api/uploads/(.+)$#', $path, $matches)) {
        stream_uploaded_file(rawurldecode($matches[1]));
    }

    if ($method === 'DELETE' && preg_match('#^/api/uploads/(.+)$#', $path, $matches)) {
        json_response(['file' => delete_uploaded_file(rawurldecode($matches[1]))]);
    }

    if ($method === 'POST' && $path === '/api/captures') {
        $capture = create_capture(read_request_payload(), normalize_uploaded_files());
        json_response(['capture' => $capture], 201);
    }

    json_error('Ruta no encontrada.', 404);
} catch (Throwable $error) {
    json_error($error->getMessage(), 500);
}
