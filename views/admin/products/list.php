<?php
require __DIR__ . '/../../../controllers/ProductsController.php';
$controller = new ProductsController();
$products = $controller->getAll();

// Start output buffering to capture content and inject it into the layout
ob_start();
?>

<h2 class="text-2xl mb-5">Product List</h2>
<a href="/vending-machine/admin/products/create" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Add New Product</a>

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">ID</th>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Price</th>
            <th class="border px-4 py-2">Quantity Available</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td class="border px-4 py-2"><?php echo $product['id']; ?></td>
                <td class="border px-4 py-2"><?php echo $product['name']; ?></td>
                <td class="border px-4 py-2"><?php echo $product['price']; ?></td>
                <td class="border px-4 py-2"><?php echo $product['quantity_available']; ?></td>
                <td class="border px-4 py-2">
                    <a href="/vending-machine/admin/products/edit?id=<?php echo $product['id']; ?>" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Edit</a>
                    <a href="/vending-machine/admin/products/delete?id=<?php echo $product['id']; ?>" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <a href="?page=<?php echo $page - 1; ?>" class="text-blue-500 hover:text-blue-600">Previous</a>
    <a href="?page=<?php echo $page + 1; ?>" class="text-blue-500 hover:text-blue-600">Next</a>
</div>

<?php
// Capture the output buffer and assign it to $content
$content = ob_get_clean();
require __DIR__ . '/../layout.php'; // Include the admin layout and inject the content
?>
