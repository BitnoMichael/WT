<?php
declare(strict_types=1);
require_once 'entity/Attraction.php';

class AttractionsRepository {
    private mysqli $conn;
    public function getLength(): int
    {
        $result = $this->conn->query("SELECT COUNT(*) AS row_count FROM attractions");

        if ($result) {
            $row = $result->fetch_assoc();
            return intval($row['row_count']);
        } else {
            die("Ошибка выполнения запроса: " . $this->conn->error);
        }
    }

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    public function create(int $user_id, string $name, string $description, string $location): bool {
        $stmt = $this->conn->prepare("INSERT INTO attractions (user_id, name, description, location) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $name, $description, $location);
        return $stmt->execute();
    }

    public function read(int $attraction_id): Attraction {
        $stmt = $this->conn->prepare("SELECT * FROM attractions WHERE id = ?");
        $stmt->bind_param("i", $attraction_id);
        $stmt->execute();
        return new Attraction($stmt->get_result()->fetch_assoc());
    }

    public function update(int $attraction_id, int $user_id, string $name, string $description, string $location): bool {
        $stmt = $this->conn->prepare("UPDATE attractions SET user_id = ?, name = ?, description = ?, location = ? WHERE attraction_id = ?");
        $stmt->bind_param("isssi", $user_id, $name, $description, $location, $attraction_id);
        return $stmt->execute();
    }

    public function delete(int $attraction_id): bool {
        $stmt = $this->conn->prepare("DELETE FROM attractions WHERE id = ?");
        $stmt->bind_param("i", $attraction_id);
        return $stmt->execute();
    }
}