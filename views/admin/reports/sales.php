<?php
require_once __DIR__ . '/../../../controllers/ReportsController.php';

$controller = new ReportsController();

// Set default sorting and filtering options
$sortField = $_GET['sort'] ?? 'transaction_date';  // Default sorting by date
$sortOrder = $_GET['order'] ?? 'DESC';  // Default to descending order
$startDate = $_GET['start_date'] ?? null;  // Start date for filtering
$endDate = $_GET['end_date'] ?? null;  // End date for filtering

// Fetch sales data with sorting and filtering
$sales = $controller->getSalesReports($sortField, $sortOrder, $startDate, $endDate);

ob_start();
?>

<div class="container mx-auto p-5">
    <h2 class="text-3xl mb-5">Sales Reports</h2>

    <!-- Filtering and Sorting Form -->
    <form method="GET" action="" class="mb-5">
        <div class="grid grid-cols-3 gap-4 mb-4">
            <!-- Date Filters -->
            <div>
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" value="<?php echo $startDate; ?>" class="border p-2 rounded w-full">
            </div>
            <div>
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" value="<?php echo $endDate; ?>" class="border p-2 rounded w-full">
            </div>
        </div>

        <!-- Sorting Options -->
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div>
                <label for="sort">Sort By:</label>
                <select name="sort" class="border p-2 rounded w-full">
                    <option value="transaction_date" <?php echo $sortField === 'transaction_date' ? 'selected' : ''; ?>>Transaction Date</option>
                    <option value="product_name" <?php echo $sortField === 'product_name' ? 'selected' : ''; ?>>Product Name</option>
                    <option value="quantity" <?php echo $sortField === 'quantity' ? 'selected' : ''; ?>>Quantity</option>
                    <option value="total_price" <?php echo $sortField === 'total_price' ? 'selected' : ''; ?>>Total Price</option>
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

    <!-- Sales Table -->
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-4 py-2">Transaction Date</th>
                <th class="border px-4 py-2">Product Name</th>
                <th class="border px-4 py-2">Quantity</th>
                <th class="border px-4 py-2">Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $sale['transaction_date']; ?></td>
                    <td class="border px-4 py-2"><?php echo $sale['product_name']; ?></td>
                    <td class="border px-4 py-2"><?php echo $sale['quantity']; ?></td>
                    <td class="border px-4 py-2">$<?php echo number_format($sale['total_price'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
?>
