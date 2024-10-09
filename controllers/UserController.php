<?php
require_once __DIR__ . '/../Database.php';  // Use require_once to prevent multiple declarations

class UserController
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUserById($id)
    {
        return $this->db->fetch("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public function changePassword($userId, $currentPassword, $newPassword)
    {
        // Fetch the user by ID
        $user = $this->getUserById($userId);

        // Verify the current password
        if (!password_verify($currentPassword, $user['password'])) {
            throw new Exception('Current password is incorrect.');
        }

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update the password in the database
        $this->db->execute("UPDATE users SET password = ? WHERE id = ?", [$hashedPassword, $userId]);
    }

    public function getUserTransactions($userId)
    {
        // Fetch the transaction history for the user
        $query = "
        SELECT transactions.transaction_date, transactions.quantity, transactions.total_price, products.name AS product_name
        FROM transactions
        JOIN products ON transactions.product_id = products.id
        WHERE transactions.user_id = ?
        ORDER BY transactions.transaction_date DESC
    ";

        return $this->db->fetchAll($query, [$userId]);
    }

    public function getUserPaginatedTransactions($userId, $page = 1, $limit = 10, $sort = 'transaction_date', $order = 'DESC')
    {
        $offset = ($page - 1) * $limit;

        // Define allowed sort fields and orders
        $allowedSortFields = ['transaction_date', 'quantity', 'total_price', 'product_name'];
        $allowedOrder = ['ASC', 'DESC'];

        // Sanitize the sort and order inputs
        $sort = in_array($sort, $allowedSortFields) ? $sort : 'transaction_date';
        $order = in_array($order, $allowedOrder) ? $order : 'DESC';

        $query = "
    SELECT transactions.transaction_date, transactions.quantity, transactions.total_price, products.name AS product_name
    FROM transactions
    JOIN products ON transactions.product_id = products.id
    WHERE transactions.user_id = ?
    ORDER BY $sort $order
    LIMIT ? OFFSET ?
    ";

        return $this->db->fetchAll($query, [$userId, $limit, $offset]);
    }

    public function getTransactionCount($userId)
    {
        $query = "SELECT COUNT(*) AS total_transactions FROM transactions WHERE user_id = ?";
        return $this->db->fetch($query, [$userId])['total_transactions'];
    }
}
