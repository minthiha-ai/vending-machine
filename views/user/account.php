<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../controllers/UserController.php';

$userId = $_SESSION['user_id'];
$userController = new UserController();
$user = $userController->getUserById($userId);

// Generate CSRF token if it doesn't already exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    try {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception('Invalid CSRF token.');
        }

        if ($newPassword !== $confirmPassword) {
            throw new Exception('New passwords do not match.');
        }

        // Change the password via the controller
        $userController->changePassword($userId, $currentPassword, $newPassword);
        echo "<p class='text-green-500'>Password updated successfully!</p>";
    } catch (Exception $e) {
        echo "<p class='text-red-500'>Error: " . $e->getMessage() . "</p>";
    }
}

ob_start();
?>

<div class="container mx-auto p-5">
    <h2 class="text-3xl mb-5">Account Information</h2>

    <div class="bg-white p-6 rounded-md shadow-md mb-8">
        <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
    </div>

    <h2 class="text-2xl mb-4">Change Password</h2>
    <form method="POST" action="">
        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" required class="border p-2 rounded w-full mb-4">

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required class="border p-2 rounded w-full mb-4">

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" required class="border p-2 rounded w-full mb-4">

        <!-- CSRF Token Field -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Change Password</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/layout.php';  // Use the user-specific layout
?>
