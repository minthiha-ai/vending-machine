<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$title = "Login";

// Generate CSRF token if it doesn't already exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

ob_start();
?>

<form action="/vending-machine/controllers/login_handler.php" method="POST" class="max-w-md mx-auto bg-white p-6 rounded-md shadow-md">
    <div class="mb-4">
        <label class="block text-gray-700">Username</label>
        <input type="text" name="username" class="w-full px-3 py-2 border rounded-md" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Password</label>
        <input type="password" name="password" class="w-full px-3 py-2 border rounded-md" required>
    </div>

    <!-- CSRF Token Field -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <button type="submit" class="w-full bg-indigo-500 text-white py-2 rounded-md">Login</button>
</form>

<?php
$content = ob_get_clean();
require 'layouts/master.php';
?>
