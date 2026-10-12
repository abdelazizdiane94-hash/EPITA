<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
require_once __DIR__ . '/middleware/JwtAuth.php';
require_once __DIR__ . '/controllers/InventoryController.php';

try {
    $userId = (new JwtAuth())->authenticatedUserId();
    if (!$userId) {
        http_response_code(401);
        echo json_encode(['error' => 'Valid bearer token required.']);
        exit;
    }
    require __DIR__ . '/config/db.php';
    $controller = new InventoryController($pdo);
    $path = trim((string) ($_SERVER['PATH_INFO'] ?? ''), '/');
    if ($path === '') {
        $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        $path = trim(substr($requestPath, strpos($requestPath, 'index.php') + strlen('index.php')), '/');
    }
    $segments = explode('/', $path);
    if (($segments[0] ?? '') !== 'inventory') {
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found.']);
        exit;
    }
    $id = $segments[1] ?? null;
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($id !== null) { http_response_code(404); echo json_encode(['error' => 'Endpoint not found.']); }
            else $controller->getInventory();
            break;
        case 'POST':
            if ($id !== null) { http_response_code(404); echo json_encode(['error' => 'Endpoint not found.']); }
            else $controller->addInventoryItem();
            break;
        case 'PUT':
            if ($id === null || !ctype_digit($id)) { http_response_code(400); echo json_encode(['error' => 'A numeric item id is required.']); }
            else $controller->updateInventoryItem($id);
            break;
        case 'DELETE':
            if ($id === null || !ctype_digit($id)) { http_response_code(400); echo json_encode(['error' => 'A numeric item id is required.']); }
            else $controller->deleteInventoryItem($id);
            break;
        default:
            header('Allow: GET, POST, PUT, DELETE');
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed.']);
    }
} catch (Throwable $exception) {
    error_log('StockPulse API request failed: ' . $exception->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'The request could not be completed.'], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
}