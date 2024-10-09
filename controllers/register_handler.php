<?php
session_start();
require '../Database.php';  // Updated to go up one directory

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "Invalid CSRF token.";
        exit();
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the username or email already exists
    $existingUser = $db->fetch("SELECT * FROM users WHERE username = ? OR email = ?", [$username, $email]);
    if ($existingUser) {
        echo "Username or email already exists.";
        exit();
    }

    // Hash the password securely using bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user into the database with 'User' role by default
    $db->execute("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'User')", [$username, $email, $hashedPassword]);

    // Optionally, log the user in immediately after registration
    $userId = $db->lastInsertId();
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = 'User';

    // Redirect the user to their dashboard after registration
    header('Location: /vending-machine/user/dashboard');
    exit();
}
