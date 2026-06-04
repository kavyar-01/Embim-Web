<?php
require_once __DIR__ . '/../config/database.php';

class DashboardModel {
    public function getCars(): array {
        return getPDO()->query("SELECT * FROM `cars` ORDER BY `id` ASC")->fetchAll();
    }

    public function getBookings(): array {
        return getPDO()->query("
            SELECT
                b.`id`, b.`user_id`, b.`car_id`,
                b.`start_date`, b.`end_date`, b.`total_days`,
                b.`total_price`, b.`status`, b.`notes`,
                b.`created_at`, b.`updated_at`,
                u.`full_name` AS customer_name,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name
            FROM `bookings` b
            JOIN `users` u ON u.`id` = b.`user_id`
            JOIN `cars`  c ON c.`id` = b.`car_id`
            ORDER BY b.`id` ASC
        ")->fetchAll();
    }

    /**
     * Ambil booking dengan filter status + search (prepared statement).
     */
    public function getBookingsFiltered(string $status = '', string $search = ''): array {
        $sql = "
            SELECT
                b.`id`, b.`user_id`, b.`car_id`,
                b.`start_date`, b.`end_date`, b.`total_days`,
                b.`total_price`, b.`status`, b.`notes`,
                b.`created_at`, b.`updated_at`,
                u.`full_name`  AS customer_name,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name,
                c.`license_plate`
            FROM `bookings` b
            JOIN `users` u ON u.`id` = b.`user_id`
            JOIN `cars`  c ON c.`id` = b.`car_id`
            WHERE 1=1
        ";
        $params = [];

        if ($status !== '') {
            $sql .= " AND b.`status` = :status";
            $params[':status'] = $status;
        }

        if ($search !== '') {
            $sql .= " AND (
                CAST(b.`id` AS CHAR) LIKE :search
                OR u.`full_name` LIKE :search
                OR CONCAT(c.`brand`, ' ', c.`model`) LIKE :search
                OR c.`license_plate` LIKE :search
            )";
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY b.`id` DESC";

        $stmt = getPDO()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getStats(array $cars, array $bookings): array {
        return [
            'total_cars'      => count($cars),
            'active_bookings' => count(array_filter($bookings, fn($b) => $b['status'] === 'confirmed')),
            'revenue'         => array_sum(array_column($bookings, 'total_price')),
            'available_cars'  => count(array_filter($cars, fn($c) => $c['status'] === 'available')),
        ];
    }
}
