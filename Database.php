<?php

class Database
{
    private $host = 'localhost';
    private $db = 'db_vending_machine'; // Your database name
    private $user = 'root';           // Your database username
    private $pass = 'root';               // Your database password
    private $charset = 'utf8mb4';     // Charset for the connection
    private $pdo;
    private $error;

    public function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass,
                $options
            );
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // Query method to prepare and execute SQL statements
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Method to fetch all rows from a result set
    public function fetchAll($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll();
    }

    // Method to fetch a single row from a result set
    public function fetch($sql, $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    // Method to execute an INSERT/UPDATE/DELETE query
    public function execute($sql, $params = [])
    {
        return $this->query($sql, $params);
    }

    // Get the last inserted ID after an INSERT statement
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
