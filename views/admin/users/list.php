<?php
require_once __DIR__ . '/../../../controllers/UsersController.php';

$userController = new UsersController();
$users = $userController->getAllUsers();

ob_start();
?>

<div class="container mx-auto p-5">
    <h2 class="text-3xl mb-5">User Management</h2>

    <!-- Users Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-4 py-2">Username</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Role</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $user['username']; ?></td>
                    <td class="border px-4 py-2"><?php echo $user['email']; ?></td>
                    <td class="border px-4 py-2"><?php echo $user['role']; ?></td>
                    <td class="border px-4 py-2">
                        <a href="/vending-machine/admin/users/edit?id=<?php echo $user['id']; ?>" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Edit</a>
                        <a href="/vending-machine/admin/users/delete?id=<?php echo $user['id']; ?>" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
?>
