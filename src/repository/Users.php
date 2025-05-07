<?php
declare(strict_types=1);

class Users {
    private mysqli $conn;
    public function getLength(): int
    {
        $result = $this->conn->query("SELECT COUNT(*) AS row_count FROM users");

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

    public function create(string $username, string $email, string $password_hash): bool {
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password_hash);
        return $stmt->execute();
    }

    public function read(int $user_id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update(int $user_id, string $username, string $email, string $password_hash): bool {
        $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ?, password_hash = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $username, $email, $password_hash, $user_id);
        return $stmt->execute();
    }

    public function delete(int $user_id): bool {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
}