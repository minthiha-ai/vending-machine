<?php
session_start();
$basePath = '/vending-machine';
$uri = str_replace($basePath, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

switch ($uri) {
    case '/':
    case '':
        require 'views/home.php';  // Home page
        break;

    case '/login':
        require 'views/login.php';  // Login page
        break;

    case '/register':
        require 'views/register.php';  // Register page
        break;

    case '/logout':
        require 'controllers/logout.php';  // Logout
        break;

    case '/user/dashboard':
        checkUser();  // Ensure the user is logged in
        require 'views/user/dashboard.php';  // Load the user dashboard
        break;

    case '/user/purchase':
        checkUser();  // Ensure the user is logged in
        require 'views/user/purchase.php';  // Load the purchase page
        break;

    case '/user/account':
        checkUser();  // Ensure the user is logged in
        require 'views/user/account.php';  // Load the account page
        break;

    case '/user/purchase-history':
        checkUser();  // Ensure the user is logged in
        require 'views/user/purchase-history.php';  // Load the purchase history page
        break;

    case '/admin/dashboard':
        checkAdmin();
        require 'views/admin/dashboard.php'; // Admin overview/dashboard page
        break;

    case '/admin/products':
        checkAdmin();
        require 'views/admin/products/list.php'; // Product listing
        break;

    case '/admin/products/create':
        checkAdmin();
        require 'views/admin/products/create.php'; // Product creation
        break;

    case '/admin/products/edit':
        checkAdmin();
        require 'views/admin/products/edit.php'; // Product editing
        break;

    case '/admin/products/delete':
        checkAdmin();
        require 'views/admin/products/delete.php'; // Product deletion
        break;

        // Admin - Reports
    case '/admin/reports/sales':
        checkAdmin();
        require 'views/admin/reports/sales.php';
        break;

    case '/admin/reports/inventory':
        checkAdmin();
        require 'views/admin/reports/inventory.php';
        break;

        // Admin - User Management
    case '/admin/users':
        checkAdmin();
        require 'views/admin/users/list.php';
        break;

    case '/admin/users/edit':
        checkAdmin();
        require 'views/admin/users/edit.php';
        break;

    case '/admin/users/delete':
        checkAdmin();
        require 'views/admin/users/delete.php';
        break;

    case '/admin/reports/inventory':
        checkAdmin();
        require 'views/admin/reports/inventory.php';
        break;

    case '/admin/reports/sales':
        checkAdmin();
        require 'views/admin/reports/sales.php';
        break;

    default:
        http_response_code(404);
        echo "Page not found.";
        break;
}
// Ensure the user is both logged in and has the 'Admin' role
function checkAdmin()
{
    if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin'
    ) {
        header('Location: /vending-machine/login');
        exit();
    }
}

function checkUser()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /vending-machine/login');
        exit();
    }
}
