<?php
session_start();
require '../Database.php';

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "Invalid CSRF token.";
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the user from the database by username
    $user = $db->fetch("SELECT * FROM users WHERE username = ?", [$username]);

    // Check if the user exists and if the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables, including the role
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];  // Store role in session

        // Redirect based on role
        if (strtolower($user['role']) === 'admin') {  // Handle case-insensitivity
            header('Location: /vending-machine/admin/dashboard');
        } else {
            header('Location: /vending-machine/user/dashboard');
        }
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
