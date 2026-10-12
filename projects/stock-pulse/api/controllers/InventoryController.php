<?php
class InventoryController {
    private $db;

    public function __construct() {
        // Include the database configuration file
        require_once '../config/db.php';
        $this->db = new Database();
    }

    // Method to get all inventory items
    public function getAllItems() {
        $query = "SELECT * FROM products";
        $stmt = $this->db->connect()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get a single inventory item by ID
    public function getItemById($id) {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->connect()->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to create a new inventory item
    public function createItem($data) {
        $query = "INSERT INTO products (name, quantity, price) VALUES (:name, :quantity, :price)";
        $stmt = $this->db->connect()->prepare($query);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':price', $data['price']);
        return $stmt->execute();
    }

    // Method to update an existing inventory item
    public function updateItem($id, $data) {
        $query = "UPDATE products SET name = :name, quantity = :quantity, price = :price WHERE id = :id";
        $stmt = $this->db->connect()->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':price', $data['price']);
        return $stmt->execute();
    }

    // Method to delete an inventory item
    public function deleteItem($id) {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->connect()->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>