<?php
// StockPulse API - Main Entry Point

// Include database configuration
require_once __DIR__ . '/config/db.php';

// Include JWT middleware for authentication
require_once __DIR__ . '/middleware/JwtAuth.php';

// Include the Inventory Controller
require_once __DIR__ . '/controllers/InventoryController.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Get the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Parse the URL to get the endpoint
$requestUri = explode('/', trim($_SERVER['PATH_INFO'], '/'));

// Define the controller and method based on the request
$controller = new InventoryController();

switch ($requestMethod) {
    case 'GET':
        // Handle GET requests
        if (isset($requestUri[1]) && $requestUri[1] === 'inventory') {
            $controller->getInventory();
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Endpoint not found']);
        }
        break;

    case 'POST':
        // Handle POST requests
        if (isset($requestUri[1]) && $requestUri[1] === 'inventory') {
            $controller->addInventoryItem();
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Endpoint not found']);
        }
        break;

    case 'PUT':
        // Handle PUT requests
        if (isset($requestUri[1]) && $requestUri[1] === 'inventory' && isset($requestUri[2])) {
            $controller->updateInventoryItem($requestUri[2]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Endpoint not found']);
        }
        break;

    case 'DELETE':
        // Handle DELETE requests
        if (isset($requestUri[1]) && $requestUri[1] === 'inventory' && isset($requestUri[2])) {
            $controller->deleteInventoryItem($requestUri[2]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Endpoint not found']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        break;
}
?>