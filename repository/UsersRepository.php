<?php
declare(strict_types=1);

require_once './.constants/Constants.php';
require_once 'entity/User.php';

class UsersRepository {
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

    public function create(string $username, string $password_hash, string $salt, bool $rememberMe, string $email): bool {
        $stmt = $this->conn->prepare("INSERT INTO users (username, password_hash, salt, remember_me, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $username, $password_hash, $salt, $rememberMe, $email);
        return $stmt->execute();
    }

    public function read(string $name): ?User {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row == null)
            return null;
        else
            return new User($row);
    }
    public function readByID(int $id): ?User {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row == null)
            return null;
        else
            return new User($row);
    }

    public function update(int $user_id, string $username, string $email, string $password_hash): bool {
        $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ?, password_hash = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $password_hash, $user_id);
        return $stmt->execute();
    }

    public function updateToken(int $user_id, string $token): bool
    {
        $stmt = $this->conn->prepare("UPDATE users SET token = ? WHERE id = ?");
        $stmt->bind_param("si", $token, $user_id);
        return $stmt->execute();
    }public function updateIsVerified(int $user_id, bool $isVerified): bool
    {
        $stmt = $this->conn->prepare("UPDATE users SET is_verified = ? WHERE id = ?");
        $stmt->bind_param("ii", $isVerified, $user_id);
        return $stmt->execute();
    }

    public function delete(int $user_id): bool {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
}