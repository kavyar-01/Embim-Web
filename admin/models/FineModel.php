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
     * Ambil semua data denda dengan relasi ke returns, bookings, users, cars.
     * Support filter status dan search (booking_id, return_id, nama pelanggan, nama mobil).
     */
    public function getAllFines(string $status = '', string $search = ''): array
    {
        $sql = "
            SELECT
                f.`id`,
                f.`return_id`,
                f.`booking_id`,
                f.`late_days`,
                f.`fine_per_day`,
                f.`fine_amount`,
                f.`status`,
                f.`created_at`,
                r.`return_date`,
                r.`car_condition`,
                r.`notes`          AS return_notes,
                u.`full_name`      AS customer_name,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name,
                c.`license_plate`
            FROM `fines` f
            JOIN `returns`  r ON r.`id`  = f.`return_id`
            JOIN `bookings` b ON b.`id`  = f.`booking_id`
            JOIN `users`    u ON u.`id`  = b.`user_id`
            JOIN `cars`     c ON c.`id`  = b.`car_id`
            WHERE 1=1
        ";
        $params = [];

        if ($status !== '') {
            $sql .= " AND f.`status` = :status";
            $params[':status'] = $status;
        }

        if ($search !== '') {
            $sql .= " AND (
                CAST(f.`booking_id` AS CHAR) LIKE :search
                OR CAST(f.`return_id`  AS CHAR) LIKE :search
                OR u.`full_name`               LIKE :search
                OR CONCAT(c.`brand`, ' ', c.`model`) LIKE :search
            )";
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY f.`id` DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Ambil satu denda beserta seluruh data relasi.
     */
    public function getFineById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                f.`id`,
                f.`return_id`,
                f.`booking_id`,
                f.`late_days`,
                f.`fine_per_day`,
                f.`fine_amount`,
                f.`status`,
                f.`created_at`,
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
            FROM `fines` f
            JOIN `returns`  r ON r.`id`  = f.`return_id`
            JOIN `bookings` b ON b.`id`  = f.`booking_id`
            JOIN `users`    u ON u.`id`  = b.`user_id`
            JOIN `cars`     c ON c.`id`  = b.`car_id`
            WHERE f.`id` = :id
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
            UPDATE `fines`
            SET `status` = :status
            WHERE `id`   = :id
        ");
        return $stmt->execute([
            ':status' => $status,
            ':id'     => $id,
        ]);
    }
}
