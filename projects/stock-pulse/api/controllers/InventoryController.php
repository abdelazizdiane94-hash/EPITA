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
        $this->db->beginTransaction();
        try {
            $find = $this->db->prepare('SELECT quantity FROM products WHERE id = :id FOR UPDATE');
            $find->execute([':id' => (int) $id]);
            $current = $find->fetch();
            if (!$current) {
                $this->db->rollBack();
                return $this->respond(['error' => 'Inventory item not found.'], 404);
            }

            $quantity = (int) $data['quantity'];
            $statement = $this->db->prepare('UPDATE products SET name = :name, quantity = :quantity, price = :price WHERE id = :id');
            $statement->execute([':name' => trim($data['name']), ':quantity' => $quantity, ':price' => (float) $data['price'], ':id' => (int) $id]);
            $difference = $quantity - (int) $current['quantity'];
            if ($difference !== 0) {
                $this->recordMovement((int) $id, abs($difference), $difference > 0 ? 'IN' : 'OUT');
            }
            $this->db->commit();
            $this->respond(['updated' => true]);
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $exception;
        }
    }

    public function deleteInventoryItem($id) {
        $statement = $this->db->prepare('DELETE FROM products WHERE id = :id');
        $statement->execute([':id' => (int) $id]);
        if ($statement->rowCount() === 0) return $this->respond(['error' => 'Inventory item not found.'], 404);
        $this->respond(['deleted' => true]);
    }

    private function requestData() {
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    private function validItem(array $data) {
        if (!isset($data['name'], $data['quantity'], $data['price']) || !is_string($data['name']) || trim($data['name']) === '' || strlen($data['name']) > 255) {
            return false;
        }
        if (!is_int($data['quantity']) || $data['quantity'] < 0 || $data['quantity'] > 4294967295) {
            return false;
        }
        if ((!is_int($data['price']) && !is_float($data['price'])) || !is_finite((float) $data['price'])) {
            return false;
        }
        return $data['price'] >= 0 && $data['price'] <= 99999999.99;
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