<?php
class InventoryController {
    private $db;

    public function __construct(PDO $database) {
        $this->db = $database;
    }

    public function getInventory() {
        $statement = $this->db->prepare('SELECT id, name, quantity, price FROM products ORDER BY name');
        $statement->execute();
        $this->respond($statement->fetchAll());
    }

    public function addInventoryItem() {
        $data = $this->requestData();
        if (!$this->validItem($data)) return $this->respond(['error' => 'Invalid inventory item.'], 422);
        $this->db->beginTransaction();
        try {
            $statement = $this->db->prepare('INSERT INTO products (name, quantity, price) VALUES (:name, :quantity, :price)');
            $statement->execute([':name' => trim($data['name']), ':quantity' => (int) $data['quantity'], ':price' => (float) $data['price']]);
            $id = (int) $this->db->lastInsertId();
            if ((int) $data['quantity'] > 0) $this->recordMovement($id, (int) $data['quantity'], 'IN');
            $this->db->commit();
            $this->respond(['id' => $id], 201);
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $exception;
        }
    }

    public function updateInventoryItem($id) {
        $data = $this->requestData();
        if (!$this->validItem($data)) return $this->respond(['error' => 'Invalid inventory item.'], 422);
        $statement = $this->db->prepare('UPDATE products SET name = :name, quantity = :quantity, price = :price WHERE id = :id');
        $statement->execute([':name' => trim($data['name']), ':quantity' => (int) $data['quantity'], ':price' => (float) $data['price'], ':id' => (int) $id]);
        $this->respond(['updated' => $statement->rowCount() > 0]);
    }

    public function deleteInventoryItem($id) {
        $statement = $this->db->prepare('DELETE FROM products WHERE id = :id');
        $statement->execute([':id' => (int) $id]);
        $this->respond(['deleted' => $statement->rowCount() > 0]);
    }

    private function requestData() {
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    private function validItem(array $data) {
        return isset($data['name'], $data['quantity'], $data['price']) && is_string($data['name']) && trim($data['name']) !== '' && strlen($data['name']) <= 255 && filter_var($data['quantity'], FILTER_VALIDATE_INT) !== false && (int) $data['quantity'] >= 0 && is_numeric($data['price']) && (float) $data['price'] >= 0;
    }

    private function recordMovement(int $productId, int $quantity, string $type) {
        $statement = $this->db->prepare('INSERT INTO stock_movements (product_id, quantity, movement_type) VALUES (:product_id, :quantity, :movement_type)');
        $statement->execute([':product_id' => $productId, ':quantity' => $quantity, ':movement_type' => $type]);
    }

    private function respond(array $data, int $status = 200) {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }
}