<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = "Register";

// Generate CSRF token if it doesn't already exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

ob_start();
?>

<form action="/vending-machine/controllers/register_handler.php" method="POST" class="max-w-lg mx-auto bg-white p-8 rounded-md shadow-md">
    <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
        <input type="text" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
        <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
        <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <!-- CSRF Token Field -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <button type="submit" class="w-full bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600">Register</button>
</form>

<?php
$content = ob_get_clean();
require 'layouts/master.php';
?>
