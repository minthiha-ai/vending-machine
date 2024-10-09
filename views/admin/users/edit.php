<?php
require_once __DIR__ . '/../../../controllers/UsersController.php';

$userController = new UsersController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $newRole = $_POST['role'];
    $userController->updateUserRole($userId, $newRole);
    header('Location: /vending-machine/admin/users');  // Redirect to the user management page after update
    exit();
}

// Get the user by ID from the query parameter
$userId = $_GET['id'];
$user = $userController->getUserById($userId);

if (!$user) {
    echo "User not found.";
    exit();
}

ob_start();
?>

<div class="container mx-auto p-5">
    <h2 class="text-3xl mb-5">Edit User</h2>

    <form method="POST" action="">
        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

        <div class="mb-4">
            <label class="block text-gray-700">Username</label>
            <input type="text" value="<?php echo $user['username']; ?>" class="w-full px-3 py-2 border rounded-md" disabled>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" value="<?php echo $user['email']; ?>" class="w-full px-3 py-2 border rounded-md" disabled>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Role</label>
            <select name="role" class="w-full px-3 py-2 border rounded-md" required>
                <option value="User" <?php echo $user['role'] === 'User' ? 'selected' : ''; ?>>User</option>
                <option value="Admin" <?php echo $user['role'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
?>
