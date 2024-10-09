<?php
require_once __DIR__ . '/../Database.php';

class ProductsController
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: new Database();
    }


    public function setDb($db)
    {
        $this->db = $db;
    }

    // Create a new product
    public function create($name,
        $price,
        $quantity
    ) {
        if (empty($name) || $price <= 0 || $quantity < 0
        ) {
            throw new Exception("Invalid product data");
        }

        $this->db->execute("INSERT INTO products (name, price, quantity_available) VALUES (?, ?, ?)", [$name, $price, $quantity]);
    }

    // Read all products
    public function getAll()
    {
        return $this->db->fetchAll("SELECT * FROM products");
    }

    // Read a single product by ID
    public function getById($id)
    {
        return $this->db->fetch("SELECT * FROM products WHERE id = ?", [$id]);
    }

    // Update a product
    public function update($id, $name, $price, $quantity)
    {
        $this->db->execute("UPDATE products SET name = ?, price = ?, quantity_available = ? WHERE id = ?", [$name, $price, $quantity, $id]);
    }

    // Delete a product
    public function delete($id)
    {
        $this->db->execute("UPDATE products SET deleted_at = NOW() WHERE id = ?", [$id]);
    }

    public function purchaseProduct($userId, $productId, $quantity)
    {
        // Fetch the product to ensure it exists and get the current quantity
        $product = $this->getById($productId);
        if (!$product) {
            throw new Exception("Product not found.");
        }

        // Check if there's enough quantity available
        if ($product['quantity_available'] < $quantity) {
            throw new Exception("Not enough quantity available.");
        }

        // Calculate the total price
        $totalPrice = $product['price'] * $quantity;

        // Reduce the product quantity
        $newQuantity = $product['quantity_available'] - $quantity;
        $this->db->execute("UPDATE products SET quantity_available = ? WHERE id = ?", [$newQuantity, $productId]);

        // Log the transaction
        $this->db->execute(
            "INSERT INTO transactions (user_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)",
            [$userId, $productId, $quantity, $totalPrice]
        );

        return true;
    }

    public function getPaginatedProducts($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        $query = "SELECT * FROM products LIMIT ? OFFSET ?";
        return $this->db->fetchAll($query, [$limit, $offset]);
    }

    public function getProductCount()
    {
        $query = "SELECT COUNT(*) AS total_products FROM products";
        return $this->db->fetch($query)['total_products'];
    }
    public function getInventory()
    {
        return $this->db->fetchAll("SELECT id, name, price, quantity_available FROM products ORDER BY name ASC");
    }
}
