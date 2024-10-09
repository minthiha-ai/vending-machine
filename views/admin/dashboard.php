<?php
// Start session and check if the admin is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: /vending-machine/login');
    exit();
}

ob_start();
?>

<h2 class="text-2xl mb-5">Admin Dashboard</h2>

<div class="grid grid-cols-3 gap-4">
    <!-- Product Management Widget -->
    <div class="bg-white p-6 rounded-md shadow-md">
        <h3 class="text-xl font-semibold mb-2">Product Management</h3>
        <p>Manage the products in your vending machine system.</p>
        <a href="/vending-machine/admin/products" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mt-4 inline-block">Manage Products</a>
    </div>

    <!-- User Management Widget -->
    <div class="bg-white p-6 rounded-md shadow-md">
        <h3 class="text-xl font-semibold mb-2">User Management</h3>
        <p>Manage the users of the vending machine system.</p>
        <a href="/vending-machine/admin/users" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 mt-4 inline-block">Manage Users</a>
    </div>

    <!-- Inventory Reports Widget -->
    <div class="bg-white p-6 rounded-md shadow-md">
        <h3 class="text-xl font-semibold mb-2">Inventory Reports</h3>
        <p>View the inventory status of products.</p>
        <a href="/vending-machine/admin/reports/inventory" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 mt-4 inline-block">Inventory Reports</a>
    </div>

    <!-- Sales Reports Widget -->
    <div class="bg-white p-6 rounded-md shadow-md">
        <h3 class="text-xl font-semibold mb-2">Sales Reports</h3>
        <p>View sales reports of transactions in the vending machine system.</p>
        <a href="/vending-machine/admin/reports/sales" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 mt-4 inline-block">Sales Reports</a>
    </div>
</div>

<?php
// Capture the output buffer and inject it into the admin layout
$content = ob_get_clean();
require 'layout.php';
?>
