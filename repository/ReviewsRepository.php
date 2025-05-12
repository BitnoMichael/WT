<?php
declare(strict_types=1);
require_once 'entity/Review.php';

class ReviewsRepository {
    private mysqli $conn;
    public function getLength(): int
    {
        $result = $this->conn->query("SELECT COUNT(*) AS row_count FROM reviews");

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

    public function create(int $attraction_id, int $user_id, int $rating, string $comment): bool {
        $stmt = $this->conn->prepare("INSERT INTO reviews (attraction_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $attraction_id, $user_id, $rating, $comment);
        return $stmt->execute();
    }

    public function read(int $review_id): Review {
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE review_id = ?");
        $stmt->bind_param("i", $review_id);
        $stmt->execute();
        return new Review($stmt->get_result()->fetch_assoc());
    }

    public function update(int $review_id, int $attraction_id, int $user_id, int $rating, string $comment): bool {
        $stmt = $this->conn->prepare("UPDATE reviews SET attraction_id = ?, user_id = ?, rating = ?, comment = ? WHERE review_id = ?");
        $stmt->bind_param("iiisi", $attraction_id, $user_id, $rating, $comment, $review_id);
        return $stmt->execute();
    }

    public function delete(int $review_id): bool {
        $stmt = $this->conn->prepare("DELETE FROM reviews WHERE review_id = ?");
        $stmt->bind_param("i", $review_id);
        return $stmt->execute();
    }
}