<?php
require_once __DIR__ . '/../../../controllers/UsersController.php';

$userController = new UsersController();

// Get the user by ID from the query parameter
$userId = $_GET['id'];

// Perform the deletion
$userController->deleteUser($userId);

// Redirect to the user management page after deletion
header('Location: /vending-machine/admin/users');
exit();
