<?php
require_once __DIR__ . '/../config/database.php';

class ReviewModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = getPDO();
    }

    /**
     * Get all reviews, joining with users and cars.
     */
    public function getAllReviews(): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                r.id, 
                r.rating, 
                r.comment, 
                r.created_at,
                u.full_name as customer_name,
                u.id as user_id,
                CONCAT(c.brand, ' ', c.model) as car_name
            FROM `reviews` r
            JOIN `users` u ON u.id = r.user_id
            JOIN `cars` c ON c.id = r.car_id
            ORDER BY r.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a specific review by ID.
     */
    public function getReviewById(int $id): ?array {
        $stmt = $this->pdo->prepare("
            SELECT 
                r.id, 
                r.rating, 
                r.comment, 
                r.created_at,
                u.full_name as customer_name,
                CONCAT(c.brand, ' ', c.model) as car_name
            FROM `reviews` r
            JOIN `users` u ON u.id = r.user_id
            JOIN `cars` c ON c.id = r.car_id
            WHERE r.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Update review details.
     */
    public function updateReview(int $id, int $rating, string $comment): bool {
        $stmt = $this->pdo->prepare("
            UPDATE `reviews`
            SET `rating` = :rating, `comment` = :comment
            WHERE `id` = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':rating' => $rating,
            ':comment' => $comment
        ]);
    }

    /**
     * Delete a review.
     */
    public function deleteReview(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM `reviews` WHERE `id` = :id");
        return $stmt->execute([':id' => $id]);
    }
}
