<?php
require_once __DIR__ . '/../../controllers/UserController.php';

$userId = $_SESSION['user_id'];
$userController = new UserController();

// Get current page number and sorting parameters from query string
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'transaction_date';  // Default sort by transaction date
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';  // Default order is descending
$limit = 10;  // Number of transactions per page

// Fetch paginated and sorted transactions
$transactions = $userController->getUserPaginatedTransactions($userId, $page, $limit, $sort, $order);
$totalTransactions = $userController->getTransactionCount($userId);  // Total transactions for pagination

// Calculate total pages
$totalPages = ceil($totalTransactions / $limit);

ob_start();
?>

<div class="container mx-auto p-5">
    <h2 class="text-3xl mb-5">Purchase History</h2>

    <!-- Sorting Options -->
    <form method="GET" action="">
        <label for="sort">Sort by:</label>
        <select name="sort" class="border p-2 rounded w-full mb-4" onchange="this.form.submit()">
            <option value="transaction_date" <?php if ($sort == 'transaction_date') echo 'selected'; ?>>Transaction Date</option>
            <option value="product_name" <?php if ($sort == 'product_name') echo 'selected'; ?>>Product</option>
            <option value="quantity" <?php if ($sort == 'quantity') echo 'selected'; ?>>Quantity</option>
            <option value="total_price" <?php if ($sort == 'total_price') echo 'selected'; ?>>Total Price</option>
        </select>

        <label for="order">Order:</label>
        <select name="order" class="border p-2 rounded w-full mb-4" onchange="this.form.submit()">
            <option value="ASC" <?php if ($order == 'ASC') echo 'selected'; ?>>Ascending</option>
            <option value="DESC" <?php if ($order == 'DESC') echo 'selected'; ?>>Descending</option>
        </select>

        <input type="hidden" name="page" value="<?php echo $page; ?>"> <!-- Keep the current page number -->
    </form>

    <!-- Transaction History Table -->
    <?php if (count($transactions) > 0): ?>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Transaction Date</th>
                    <th class="border px-4 py-2">Product</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $transaction['transaction_date']; ?></td>
                        <td class="border px-4 py-2"><?php echo $transaction['product_name']; ?></td>
                        <td class="border px-4 py-2"><?php echo $transaction['quantity']; ?></td>
                        <td class="border px-4 py-2">$<?php echo $transaction['total_price']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no purchase history.</p>
    <?php endif; ?>

    <!-- Pagination Links -->
    <div class="mt-5">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Previous</a>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Next</a>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/layout.php';  // Use the user-specific layout
?>
