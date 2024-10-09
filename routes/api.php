<?php

require_once __DIR__ . '/../controllers/api/ApiAuthController.php';
require_once __DIR__ . '/../controllers/api/ApiController.php';

// Base path for the API
$basePath = '/vending-machine/api';
$uri = str_replace($basePath, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$apiController = new ApiController();
$authController = new ApiAuthController();

switch ($uri) {
        // Auth Routes
    case '/auth/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        }
        break;

    case '/auth/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        }
        break;

        // Products Routes
    case '/products':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $apiController->getProducts();
        }
        break;

    case '/products/purchase':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $apiController->purchaseProduct();
        }
        break;

    default:
        // 404 Not Found
        http_response_code(404);
        echo json_encode(['error' => 'API endpoint not found']);
        break;
}
