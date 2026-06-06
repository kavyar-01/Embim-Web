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
                b.`payment_status`,
                b.`created_at`, b.`updated_at`,
                u.`full_name`  AS customer_name,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name,
                c.`license_plate`,
                r.`id` AS return_id, r.`late_days`, r.`car_condition`,
                (SELECT COUNT(*) FROM `reviews` rev WHERE rev.`booking_id` = b.`id`) AS review_count
            FROM `bookings` b
            JOIN `users` u ON u.`id` = b.`user_id`
            JOIN `cars`  c ON c.`id` = b.`car_id`
            LEFT JOIN `returns` r ON r.`booking_id` = b.`id`
            WHERE 1=1
        ";
        $params = [];

        if ($status !== '') {
            $sql .= " AND b.`status` = :status";
            $params[':status'] = $status;
        }

        if ($search !== '') {
            $sql .= " AND (
                CAST(b.`id` AS CHAR) LIKE :search1
                OR u.`full_name` LIKE :search2
                OR CONCAT(c.`brand`, ' ', c.`model`) LIKE :search3
                OR c.`license_plate` LIKE :search4
            )";
            $params[':search1'] = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
            $params[':search3'] = '%' . $search . '%';
            $params[':search4'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY b.`id` DESC";

        $stmt = getPDO()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getPaymentsFiltered(string $status = '', string $search = '', string $month = ''): array {
        $sql = "
            SELECT
                b.`id` AS booking_id, b.`user_id`, b.`car_id`,
                b.`total_price`, b.`payment_method`, b.`payment_status`,
                b.`payment_proof`, b.`paid_at`, b.`created_at`,
                u.`full_name` AS customer_name,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name
            FROM `bookings` b
            JOIN `users` u ON u.`id` = b.`user_id`
            JOIN `cars`  c ON c.`id` = b.`car_id`
            WHERE 1=1
        ";
        $params = [];

        if ($status !== '') {
            $sql .= " AND b.`payment_status` = :status";
            $params[':status'] = $status;
        }

        if ($search !== '') {
            $sql .= " AND (
                CAST(b.`id` AS CHAR) LIKE :search1
                OR u.`full_name` LIKE :search2
                OR CONCAT(c.`brand`, ' ', c.`model`) LIKE :search3
                OR b.`payment_method` LIKE :search4
            )";
            $params[':search1'] = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
            $params[':search3'] = '%' . $search . '%';
            $params[':search4'] = '%' . $search . '%';
        }

        if ($month !== '') {
            $sql .= " AND DATE_FORMAT(b.`created_at`, '%Y-%m') = :month";
            $params[':month'] = $month;
        }

        $sql .= " ORDER BY b.`id` DESC";

        $stmt = getPDO()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getPaymentById(int $bookingId): ?array {
        $sql = "
            SELECT
                b.`id` AS booking_id, b.`user_id`, b.`car_id`,
                b.`total_price`, b.`payment_method`, b.`payment_status`,
                b.`payment_proof`, b.`paid_at`, b.`created_at`, b.`status` AS booking_status,
                u.`full_name` AS customer_name,
                u.`email` AS customer_email,
                u.`phone` AS customer_phone,
                u.`photo_profile`,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name,
                c.`license_plate`
            FROM `bookings` b
            JOIN `users` u ON u.`id` = b.`user_id`
            JOIN `cars`  c ON c.`id` = b.`car_id`
            WHERE b.`id` = :id
            LIMIT 1
        ";
        $stmt = getPDO()->prepare($sql);
        $stmt->execute([':id' => $bookingId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function updatePaymentStatus(int $bookingId, string $status): bool {
        $allowed = ['unpaid', 'paid', 'refunded'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }
        $sql = "UPDATE `bookings` SET `payment_status` = :status, `updated_at` = NOW()";
        if ($status === 'paid') {
            $sql .= ", `paid_at` = COALESCE(`paid_at`, NOW())";
        } elseif ($status === 'unpaid') {
            $sql .= ", `paid_at` = NULL";
        }
        $sql .= " WHERE `id` = :id";
        
        $stmt = getPDO()->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':id' => $bookingId
        ]);
    }

    public function getStats(array $cars, array $bookings, string $monthYear = ''): array {
        if ($monthYear === '') {
            $monthYear = date('Y-m');
        }

        $monthlyBookings = array_filter($bookings, function($b) use ($monthYear) {
            return str_starts_with($b['created_at'], $monthYear) && in_array($b['status'], ['confirmed', 'ongoing', 'completed']);
        });

        return [
            'total_cars'      => count($cars),
            'active_bookings' => count(array_filter($bookings, fn($b) => $b['status'] === 'confirmed')),
            'revenue'         => array_sum(array_column($monthlyBookings, 'total_price')),
            'available_cars'  => count(array_filter($cars, fn($c) => $c['status'] === 'available')),
        ];
    }

    public function getRevenueChartData(string $monthYear): array {
        if ($monthYear === '') {
            $monthYear = date('Y-m');
        }
        
        $sql = "
            SELECT DATE(`created_at`) as booking_date, SUM(`total_price`) as daily_revenue
            FROM `bookings`
            WHERE DATE_FORMAT(`created_at`, '%Y-%m') = :monthYear
              AND `status` IN ('confirmed', 'ongoing', 'completed')
            GROUP BY DATE(`created_at`)
            ORDER BY DATE(`created_at`) ASC
        ";
        $stmt = getPDO()->prepare($sql);
        $stmt->execute([':monthYear' => $monthYear]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $daysInMonth = (int)date('t', strtotime($monthYear . '-01'));
        $chartData = array_fill(1, $daysInMonth, 0);

        foreach ($rows as $row) {
            $day = (int)date('j', strtotime($row['booking_date']));
            $chartData[$day] = (float)$row['daily_revenue'];
        }

        return [
            'labels' => array_keys($chartData),
            'data'   => array_values($chartData)
        ];
    }

    public function getTopCar(): ?array {
        $sql = "
            SELECT c.*, COUNT(b.id) as booking_count 
            FROM `cars` c 
            JOIN `bookings` b ON c.id = b.car_id 
            GROUP BY c.id 
            ORDER BY booking_count DESC 
            LIMIT 1
        ";
        $stmt = getPDO()->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getTopUser(): ?array {
        $sql = "
            SELECT u.*, COUNT(b.id) as booking_count 
            FROM `users` u 
            JOIN `bookings` b ON u.id = b.user_id 
            GROUP BY u.id 
            ORDER BY booking_count DESC 
            LIMIT 1
        ";
        $stmt = getPDO()->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
