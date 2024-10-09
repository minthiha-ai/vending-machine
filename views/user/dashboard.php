<?php
require_once __DIR__ . '/../../controllers/UserController.php';

$userController = new UserController();
$userId = $_SESSION['user_id'];
$user = $userController->getUserById($userId);

ob_start();
?>

<div class="container mx-auto p-5">
    <h1 class="text-3xl font-bold mb-5">Welcome, <?php echo $user['username']; ?></h1>
    <p>Select an option from the sidebar to view your account details or purchase products.</p>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/layout.php';  // Use the user-specific layout
?>
