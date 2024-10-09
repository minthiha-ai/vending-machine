<?php
require_once __DIR__ . '/../Database.php';

class UsersController
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Fetch all users
    public function getAllUsers()
    {
        $query = "SELECT id, username, email, role FROM users";
        return $this->db->fetchAll($query);
    }

    // Get a single user by ID
    public function getUserById($id)
    {
        return $this->db->fetch("SELECT * FROM users WHERE id = ?", [$id]);
    }

    // Update the user's role
    public function updateUserRole($id, $role)
    {
        $this->db->execute("UPDATE users SET role = ? WHERE id = ?", [$role, $id]);
    }

    // Delete a user by ID
    public function deleteUser($id)
    {
        $this->db->execute("DELETE FROM users WHERE id = ?", [$id]);
    }
}
