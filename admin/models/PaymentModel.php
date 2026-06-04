<?php
require_once __DIR__ . '/../config/database.php';

class PaymentModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = getPDO();
    }

    /**
     * Ambil semua booking+payment dengan filter:
     *   $status      — unpaid | paid | refunded | ''
     *   $search      — booking_id / customer name
     *   $amountMin   — batas bawah amount (Rp)
     *   $amountMax   — batas atas  amount (Rp)
     *   $timeMode    — 'range' | 'month' | 'year' | ''
     *   $dateFrom    — YYYY-MM-DD  (dipakai saat timeMode=range)
     *   $dateTo      — YYYY-MM-DD  (dipakai saat timeMode=range)
     *   $month       — YYYY-MM     (dipakai saat timeMode=month)
     *   $year        — YYYY        (dipakai saat timeMode=year)
     *
     * Kolom waktu yang difilter: b.created_at
     */
    public function getAllPayments(
        string $status    = '',
        string $search    = '',
        string $amountMin = '',
        string $amountMax = '',
        string $timeMode  = '',
        string $dateFrom  = '',
        string $dateTo    = '',
        string $month     = '',
        string $year      = ''
    ): array {
        $sql = "
            SELECT
                b.`id`, b.`id` AS `booking_id`, b.`payment_method`,
                b.`total_price` AS `amount`, b.`payment_status` AS `status`,
                b.`payment_proof`, b.`paid_at`, b.`created_at`, b.`updated_at`,
                u.`full_name` AS customer_name,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name
            FROM `bookings` b
            JOIN `users`    u ON u.`id` = b.`user_id`
            JOIN `cars`     c ON c.`id` = b.`car_id`
            WHERE 1=1
        ";
        $params = [];

        /* ── Status ────────────────────────────────────────────── */
        if ($status !== '') {
            $sql .= " AND b.`payment_status` = :status";
            $params[':status'] = $status;
        }

        /* ── Search ────────────────────────────────────────────── */
        if ($search !== '') {
            $sql .= " AND (CAST(b.`id` AS CHAR) LIKE :search
                          OR u.`full_name` LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        /* ── Rentang Harga ─────────────────────────────────────── */
        if ($amountMin !== '' && is_numeric($amountMin)) {
            $sql .= " AND b.`total_price` >= :amount_min";
            $params[':amount_min'] = (float) $amountMin;
        }
        if ($amountMax !== '' && is_numeric($amountMax)) {
            $sql .= " AND b.`total_price` <= :amount_max";
            $params[':amount_max'] = (float) $amountMax;
        }

        /* ── Rentang Waktu ─────────────────────────────────────── */
        if ($timeMode === 'range') {
            if ($dateFrom !== '') {
                $sql .= " AND DATE(b.`created_at`) >= :date_from";
                $params[':date_from'] = $dateFrom;
            }
            if ($dateTo !== '') {
                $sql .= " AND DATE(b.`created_at`) <= :date_to";
                $params[':date_to'] = $dateTo;
            }
        } elseif ($timeMode === 'month' && $month !== '') {
            // $month format: YYYY-MM
            $sql .= " AND DATE_FORMAT(b.`created_at`, '%Y-%m') = :month";
            $params[':month'] = $month;
        } elseif ($timeMode === 'year' && $year !== '') {
            $sql .= " AND YEAR(b.`created_at`) = :year";
            $params[':year'] = (int) $year;
        }

        $sql .= " ORDER BY b.`id` DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getPaymentById(int $id): ?array {
        $stmt = $this->pdo->prepare("
            SELECT
                b.`id`, b.`id` AS `booking_id`, b.`payment_method`,
                b.`total_price` AS `amount`, b.`payment_status` AS `status`,
                b.`payment_proof`, b.`paid_at`, b.`created_at`, b.`updated_at`,
                b.`start_date`, b.`end_date`, b.`total_days`,
                b.`total_price`, b.`status` AS booking_status, b.`notes`,
                u.`full_name` AS customer_name, u.`email` AS customer_email,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name,
                c.`license_plate`, c.`price_per_day`
            FROM `bookings` b
            JOIN `users`    u ON u.`id` = b.`user_id`
            JOIN `cars`     c ON c.`id` = b.`car_id`
            WHERE b.`id` = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function updateStatus(int $id, string $status): bool {
        $allowed = ['unpaid', 'paid', 'refunded'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $paidAt = ($status === 'paid') ? date('Y-m-d H:i:s') : null;

        $stmt = $this->pdo->prepare("
            UPDATE `bookings`
            SET `payment_status` = :status, `paid_at` = :paid_at, `updated_at` = NOW()
            WHERE `id` = :id
        ");
        return $stmt->execute([
            ':status'  => $status,
            ':paid_at' => $paidAt,
            ':id'      => $id,
        ]);
    }
}