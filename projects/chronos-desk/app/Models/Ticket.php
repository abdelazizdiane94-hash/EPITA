<?php
class Ticket {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function create(array $data, int $userId) {
        $this->db->beginTransaction();
        try {
            $statement = $this->db->prepare('INSERT INTO tickets (title, description, status, user_id) VALUES (:title, :description, :status, :user_id)');
            $statement->execute([':title' => $data['title'], ':description' => $data['description'], ':status' => 'open', ':user_id' => $userId]);
            $ticketId = (int) $this->db->lastInsertId();
            $this->db->commit();
            return $ticketId;
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $exception;
        }
    }

    public function getAll() {
        return $this->db->query('SELECT tickets.id, tickets.title, tickets.description, tickets.status, tickets.created_at, users.username FROM tickets JOIN users ON users.id = tickets.user_id ORDER BY tickets.created_at DESC')->fetchAll();
    }

    public function getById(int $id) {
        $statement = $this->db->prepare('SELECT tickets.id, tickets.title, tickets.description, tickets.status, tickets.created_at, users.username FROM tickets JOIN users ON users.id = tickets.user_id WHERE tickets.id = :id');
        $statement->execute([':id' => $id]);
        return $statement->fetch();
    }

    public function update(int $id, array $data) {
        $this->db->beginTransaction();
        try {
            $statement = $this->db->prepare('UPDATE tickets SET title = :title, description = :description, status = :status WHERE id = :id');
            $statement->execute([':title' => $data['title'], ':description' => $data['description'], ':status' => $data['status'], ':id' => $id]);
            $this->db->commit();
            return $statement->rowCount() > 0;
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $exception;
        }
    }

    public function delete(int $id) {
        $this->db->beginTransaction();
        try {
            $statement = $this->db->prepare('DELETE FROM tickets WHERE id = :id');
            $statement->execute([':id' => $id]);
            $this->db->commit();
            return $statement->rowCount() > 0;
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $exception;
        }
    }
}