<?php
require __DIR__ . '/../../../controllers/ProductsController.php';
$controller = new ProductsController();

$id = $_GET['id'];
$product = $controller->getById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $controller->update($id, $name, $price, $quantity);
    header('Location: /vending-machine/admin/products');
    exit();
}

ob_start();
?>

<h2 class="text-2xl mb-5">Edit Product</h2>
<form method="POST">
    <label>Name: </label>
    <input type="text" name="name" value="<?php echo $product['name']; ?>" class="border p-2 w-full" required><br><br>

    <label>Price: </label>
    <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" class="border p-2 w-full" required><br><br>

    <label>Quantity: </label>
    <input type="number" name="quantity" value="<?php echo $product['quantity_available']; ?>" class="border p-2 w-full" required><br><br>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update Product</button>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php'; // Include the layout and inject the content
?>
