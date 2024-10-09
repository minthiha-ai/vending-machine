<?php
require_once __DIR__ . '/../../../controllers/ReportsController.php';

$controller = new ReportsController();

// Set default sorting and filtering options
$sortField = $_GET['sort'] ?? 'quantity_available';  // Default sorting by quantity
$sortOrder = $_GET['order'] ?? 'ASC';  // Default to ascending order
$lowStockThreshold = $_GET['low_stock'] ?? null;  // Filter for low stock

// Fetch inventory data with sorting and filtering
$inventory = $controller->getInventoryReports($sortField, $sortOrder, $lowStockThreshold);

ob_start();
?>

<div class="container mx-auto p-5">
    <h2 class="text-3xl mb-5">Inventory Reports</h2>

    <!-- Filtering and Sorting Form -->
    <form method="GET" action="" class="mb-5">
        <div class="grid grid-cols-3 gap-4 mb-4">
            <!-- Low Stock Filter -->
            <div>
                <label for="low_stock">Low Stock Threshold:</label>
                <input type="number" name="low_stock" min="0" value="<?php echo $lowStockThreshold; ?>" class="border p-2 rounded w-full" placeholder="Enter quantity">
            </div>
        </div>

        <!-- Sorting Options -->
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div>
                <label for="sort">Sort By:</label>
                <select name="sort" class="border p-2 rounded w-full">
                    <option value="name" <?php echo $sortField === 'name' ? 'selected' : ''; ?>>Product Name</option>
                    <option value="quantity_available" <?php echo $sortField === 'quantity_available' ? 'selected' : ''; ?>>Quantity Available</option>
                </select>
            </div>
            <div>
                <label for="order">Order:</label>
                <select name="order" class="border p-2 rounded w-full">
                    <option value="ASC" <?php echo $sortOrder === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                    <option value="DESC" <?php echo $sortOrder === 'DESC' ? 'selected' : ''; ?>>Descending</option>
                </select>
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600">Apply Filters</button>
    </form>

    <!-- Inventory Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-4 py-2">Product Name</th>
                <th class="border px-4 py-2">Quantity Available</th>
                <th class="border px-4 py-2">Stock Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventory as $item): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $item['name']; ?></td>
                    <td class="border px-4 py-2"><?php echo $item['quantity_available']; ?></td>
                    <td class="border px-4 py-2"><?php echo $item['quantity_available'] < 5 ? 'Low Stock' : 'OK'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
