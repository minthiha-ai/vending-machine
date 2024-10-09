<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../../../controllers/ProductsController.php';
$controller = new ProductsController();

// Generate CSRF token if it doesn't already exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "Invalid CSRF token.";
        exit();
    }

    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $controller->create($name, $price, $quantity);
    header('Location: /vending-machine/admin/products');
    exit();
}

ob_start();
?>

<h2 class="text-2xl mb-5">Add New Product</h2>
<form method="POST">
    <label>Name: </label>
    <input type="text" name="name" class="border p-2 w-full" required><br><br>

    <label>Price: </label>
    <input type="number" step="0.01" name="price" class="border p-2 w-full" required><br><br>

    <label>Quantity: </label>
    <input type="number" name="quantity" class="border p-2 w-full" required><br><br>

    <!-- CSRF Token Field -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Create Product</button>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php'; // Include the layout and inject the content
?>
