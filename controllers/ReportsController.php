<?php
require_once __DIR__ . '/../Database.php';

class ReportsController
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Get total sales amount
    public function getTotalSales()
    {
        return $this->db->fetch("SELECT SUM(total_price) AS total_sales FROM transactions")['total_sales'];
    }

    // Get total quantity of products sold
    public function getTotalProductsSold()
    {
        return $this->db->fetch("SELECT SUM(quantity) AS total_products_sold FROM transactions")['total_products_sold'];
    }

    // Get detailed sales data
    public function getSalesDetails()
    {
        $query = "
        SELECT transactions.transaction_date, transactions.quantity, transactions.total_price,
        products.name AS product_name, users.username AS customer_name
        FROM transactions
        JOIN products ON transactions.product_id = products.id
        JOIN users ON transactions.user_id = users.id
        ORDER BY transactions.transaction_date DESC
        ";

        return $this->db->fetchAll($query);
    }

    public function getSalesReports($sortField = 'transaction_date', $sortOrder = 'DESC', $startDate = null, $endDate = null)
    {
        $params = [];
        $query = "
            SELECT transactions.transaction_date, transactions.quantity, transactions.total_price, products.name AS product_name
            FROM transactions
            JOIN products ON transactions.product_id = products.id
        ";

        // Apply date filters if provided
        if ($startDate && $endDate) {
            $query .= " WHERE transactions.transaction_date BETWEEN ? AND ? ";
            $params[] = $startDate;
            $params[] = $endDate;
        } elseif ($startDate) {
            $query .= " WHERE transactions.transaction_date >= ? ";
            $params[] = $startDate;
        } elseif ($endDate) {
            $query .= " WHERE transactions.transaction_date <= ? ";
            $params[] = $endDate;
        }

        // Apply sorting
        $query .= " ORDER BY $sortField $sortOrder";

        return $this->db->fetchAll($query, $params);
    }

    public function getAverageSalesPerTransaction($startDate = null, $endDate = null)
    {
        $query = "SELECT AVG(total_price) as average_sales FROM transactions";
        $params = [];

        if ($startDate && $endDate) {
            $query .= " WHERE transaction_date BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }

        return $this->db->fetch($query, $params)['average_sales'];
    }

    public function getInventoryReports($sortField = 'quantity_available', $sortOrder = 'ASC', $lowStockThreshold = null)
    {
        $params = [];
        $query = "SELECT name, quantity_available FROM products";

        // Apply low stock filter if provided
        if ($lowStockThreshold !== null) {
            $query .= " WHERE quantity_available <= ?";
            $params[] = $lowStockThreshold;
        }

        // Apply sorting
        $query .= " ORDER BY $sortField $sortOrder";

        return $this->db->fetchAll($query, $params);
    }
}
