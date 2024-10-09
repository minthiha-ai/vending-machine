<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../controllers/ProductsController.php';

$controller = new ProductsController();

// Generate CSRF token if it doesn't already exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "<div class='text-red-500'>Invalid CSRF token.</div>";
        exit();
    }

    $productId = $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    // Fetch the product
    $product = $controller->getById($productId);

    if (!$product) {
        echo "<div class='text-red-500'>Product not found.</div>";
        exit();
    }

    // Check if enough quantity is available
    if ($product['quantity_available'] < $quantity) {
        echo "<div class='text-red-500'>Insufficient quantity available.</div>";
        exit();
    }

    // Proceed with the purchase process
    $userId = $_SESSION['user_id'];  // Assuming the user is logged in
    $controller->purchaseProduct($userId, $productId, $quantity);

    echo "<div class='text-green-500'>Purchase successful!</div>";
}

// Fetch all products
$products = $controller->getAll();

ob_start();
?>

<!-- Display Products -->
<div class="container mx-auto p-5">
    <h2 class="text-3xl mb-5">Purchase Products</h2>

    <!-- Form Action points to the same page -->
    <form method="POST" action="/vending-machine/user/purchase">
        <label for="product_id">Select a Product:</label>
        <select name="product_id" required class="border p-2 rounded w-full mb-4">
            <?php foreach ($products as $product): ?>
                <option value="<?php echo $product['id']; ?>">
                    <?php echo $product['name']; ?> - $<?php echo $product['price']; ?> (Available: <?php echo $product['quantity_available']; ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" min="1" required class="border p-2 rounded w-full mb-4">

        <!-- CSRF Token Field -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Purchase</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/layout.php';
?>
