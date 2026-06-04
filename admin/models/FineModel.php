<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class FineModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    /**
     * Ambil semua data denda dari tabel returns.
     */
    public function getAllFines(string $status = '', string $search = ''): array
    {
        $sql = "
            SELECT
                r.`id`,
                r.`id` AS return_id,
                r.`booking_id`,
                r.`late_days`,
                r.`fine_per_day`,
                r.`fine_amount`,
                r.`fine_status` AS status,
                r.`created_at`,
                r.`return_date`,
                r.`car_condition`,
                r.`notes`          AS return_notes,
                u.`full_name`      AS customer_name,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name,
                c.`license_plate`
            FROM `returns` r
            JOIN `bookings` b ON b.`id`  = r.`booking_id`
            JOIN `users`    u ON u.`id`  = b.`user_id`
            JOIN `cars`     c ON c.`id`  = b.`car_id`
            WHERE r.`fine_amount` > 0
        ";
        $params = [];

        if ($status !== '') {
            $sql .= " AND r.`fine_status` = :status";
            $params[':status'] = $status;
        }

        if ($search !== '') {
            $sql .= " AND (
                CAST(r.`booking_id` AS CHAR) LIKE :search
                OR CAST(r.`id`  AS CHAR) LIKE :search
                OR u.`full_name`               LIKE :search
                OR CONCAT(c.`brand`, ' ', c.`model`) LIKE :search
            )";
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY r.`id` DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Ambil satu denda beserta seluruh data relasi dari tabel returns.
     */
    public function getFineById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                r.`id`,
                r.`id` AS return_id,
                r.`booking_id`,
                r.`late_days`,
                r.`fine_per_day`,
                r.`fine_amount`,
                r.`fine_status` AS status,
                r.`created_at`,
                r.`return_date`,
                r.`car_condition`,
                r.`notes`          AS return_notes,
                b.`start_date`,
                b.`end_date`,
                b.`total_days`,
                b.`total_price`,
                b.`status`         AS booking_status,
                u.`full_name`      AS customer_name,
                u.`email`          AS customer_email,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name,
                c.`license_plate`
            FROM `returns` r
            JOIN `bookings` b ON b.`id`  = r.`booking_id`
            JOIN `users`    u ON u.`id`  = b.`user_id`
            JOIN `cars`     c ON c.`id`  = b.`car_id`
            WHERE r.`id` = :id AND r.`fine_amount` > 0
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Update status denda (unpaid / paid). Hanya nilai yang valid diterima.
     */
    public function updateStatus(int $id, string $status): bool
    {
        $allowed = ['unpaid', 'paid'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            UPDATE `returns`
            SET `fine_status` = :status
            WHERE `id`   = :id
        ");
        return $stmt->execute([
            ':status' => $status,
            ':id'     => $id,
        ]);
    }
}
