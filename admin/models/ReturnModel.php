<?php
require_once __DIR__ . '/../config/database.php';

class ReturnModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = getPDO();
    }

    /**
     * Ambil semua data return, join dengan bookings, users, cars.
     * Support filter: condition, search (booking_id / customer / car), return_date.
     */
    public function getAllReturns(string $condition = '', string $search = '', string $returnDate = ''): array {
        $sql = "
            SELECT
                r.`id`,
                r.`booking_id`,
                r.`return_date`,
                r.`late_days`,
                r.`car_condition`,
                r.`fine_per_day`,
                r.`fine_amount`,
                r.`fine_status`,
                r.`notes`,
                r.`created_at`,
                b.`start_date`,
                b.`end_date`,
                b.`status`          AS booking_status,
                b.`total_price`,
                b.`payment_status`,
                u.`full_name`       AS customer_name,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name,
                c.`license_plate`,
                (SELECT COUNT(*) FROM `reviews` rev WHERE rev.`booking_id` = b.`id`) AS review_count
            FROM `returns` r
            JOIN `bookings` b ON b.`id` = r.`booking_id`
            JOIN `users`    u ON u.`id` = b.`user_id`
            JOIN `cars`     c ON c.`id` = b.`car_id`
            WHERE 1=1
        ";
        $params = [];

        if ($condition !== '') {
            $sql .= " AND r.`car_condition` = :condition";
            $params[':condition'] = $condition;
        }

        if ($search !== '') {
            $sql .= " AND (
                CAST(r.`id` AS CHAR) LIKE :search0
                OR CAST(r.`booking_id` AS CHAR) LIKE :search1
                OR u.`full_name`            LIKE :search2
                OR CONCAT(c.`brand`, ' ', c.`model`) LIKE :search3
            )";
            $params[':search0'] = '%' . $search . '%';
            $params[':search1'] = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
            $params[':search3'] = '%' . $search . '%';
        }

        if ($returnDate !== '') {
            $sql .= " AND r.`return_date` = :return_date";
            $params[':return_date'] = $returnDate;
        }

        $sql .= " ORDER BY r.`id` DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getReturnStats(): array {
        $stmt = $this->pdo->query("
            SELECT
                COUNT(*) as total,
                SUM(IF(car_condition = 'good', 1, 0)) as good_count,
                SUM(IF(car_condition = 'damaged', 1, 0)) as damaged_count
            FROM `returns`
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil satu return berdasarkan ID, lengkap dengan info booking + user + car.
     */
    public function getReturnById(int $id): ?array {
        $stmt = $this->pdo->prepare("
            SELECT
                r.`id`,
                r.`booking_id`,
                r.`return_date`,
                r.`late_days`,
                r.`car_condition`,
                r.`fine_per_day`,
                r.`fine_amount`,
                r.`fine_status`,
                r.`notes`,
                r.`created_at`,
                b.`start_date`,
                b.`end_date`,
                b.`total_days`,
                b.`total_price`,
                b.`status`          AS booking_status,
                b.`notes`           AS booking_notes,
                u.`full_name`       AS customer_name,
                u.`email`           AS customer_email,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name,
                c.`license_plate`,
                c.`id`              AS car_id
            FROM `returns` r
            JOIN `bookings` b ON b.`id` = r.`booking_id`
            JOIN `users`    u ON u.`id` = b.`user_id`
            JOIN `cars`     c ON c.`id` = b.`car_id`
            WHERE r.`id` = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Cek apakah booking_id sudah punya return (UNIQUE constraint).
     * Jika $excludeReturnId diberikan, abaikan return itu sendiri (untuk edit).
     */
    public function existsByBookingId(int $bookingId, int $excludeReturnId = 0): bool {
        $sql    = "SELECT COUNT(*) FROM `returns` WHERE `booking_id` = :bid";
        $params = [':bid' => $bookingId];
        if ($excludeReturnId > 0) {
            $sql .= " AND `id` != :eid";
            $params[':eid'] = $excludeReturnId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return ((int) $stmt->fetchColumn()) > 0;
    }

    /**
     * Ambil booking berdasarkan ID (untuk validasi dan hitung late_days).
     */
    public function getBookingById(int $bookingId): ?array {
        $stmt = $this->pdo->prepare("
            SELECT b.`id`, b.`end_date`, b.`car_id`, b.`status`
            FROM `bookings` b
            WHERE b.`id` = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $bookingId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Hitung late_days: selisih return_date - end_date jika lebih besar, else 0.
     */
    public function calcLateDays(string $returnDate, string $endDate): int {
        $ret = new DateTimeImmutable($returnDate);
        $end = new DateTimeImmutable($endDate);
        if ($ret > $end) {
            return (int) $ret->diff($end)->days;
        }
        return 0;
    }

    /**
     * Ambil semua booking yang BELUM punya return (untuk dropdown tambah).
     */
    public function getBookingsWithoutReturn(): array {
        $stmt = $this->pdo->prepare("
            SELECT
                b.`id`,
                b.`end_date`,
                b.`status`,
                u.`full_name`       AS customer_name,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name
            FROM `bookings` b
            JOIN `users` u ON u.`id` = b.`user_id`
            JOIN `cars`  c ON c.`id` = b.`car_id`
            WHERE b.`id` NOT IN (
                SELECT `booking_id` FROM `returns`
            )
            AND b.`status` = 'completed'
            ORDER BY b.`id` DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Simpan return baru + update status booking + update status car.
     */
    public function createReturn(int $bookingId, string $returnDate, string $carCondition, string $notes, float $damageFine = 0): int|false {
        $booking = $this->getBookingById($bookingId);
        if ($booking === null) {
            return false;
        }

        $lateDays = $this->calcLateDays($returnDate, $booking['end_date']);

        $this->pdo->beginTransaction();
        try {
            $finePerDay = 700000;
            $fineAmount = ($lateDays > 0 ? $lateDays * $finePerDay : 0) + $damageFine;
            $fineStatus = $fineAmount > 0 ? 'unpaid' : 'none';

            // 1. Insert ke tabel returns
            $stmt = $this->pdo->prepare("
                INSERT INTO `returns`
                    (`booking_id`, `return_date`, `late_days`, `car_condition`, `notes`, `fine_per_day`, `fine_amount`, `fine_status`)
                VALUES
                    (:bid, :rd, :ld, :cc, :notes, :fpd, :fa, :fs)
            ");
            $stmt->execute([
                ':bid'   => $bookingId,
                ':rd'    => $returnDate,
                ':ld'    => $lateDays,
                ':cc'    => $carCondition,
                ':notes' => $notes !== '' ? $notes : null,
                ':fpd'   => $finePerDay,
                ':fa'    => $fineAmount,
                ':fs'    => $fineStatus,
            ]);
            $newId = (int) $this->pdo->lastInsertId();

            // 3. Update status booking menjadi completed
            $this->pdo->prepare("
                UPDATE `bookings`
                SET `status` = 'completed', `updated_at` = NOW()
                WHERE `id` = :bid
            ")->execute([':bid' => $bookingId]);

            // 4. Update status car berdasarkan kondisi
            $carStatus = ($carCondition === 'good') ? 'available' : 'maintenance';
            $this->pdo->prepare("
                UPDATE `cars`
                SET `status` = :cs, `updated_at` = NOW()
                WHERE `id` = :cid
            ")->execute([
                ':cs'  => $carStatus,
                ':cid' => $booking['car_id'],
            ]);

            $this->pdo->commit();
            return $newId;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    /**
     * Update return (return_date, car_condition, notes) + recalc late_days + re-update car status.
     */
    public function updateReturn(int $id, string $returnDate, string $carCondition, string $notes, float $damageFine = 0, string $newFineStatus = ''): bool {
        $existing = $this->getReturnById($id);
        if ($existing === null) {
            return false;
        }

        $booking = $this->getBookingById($existing['booking_id']);
        if ($booking === null) {
            return false;
        }

        $lateDays = $this->calcLateDays($returnDate, $booking['end_date']);

        $this->pdo->beginTransaction();
        try {
            $finePerDay = 700000;
            $fineAmount = ($lateDays > 0 ? $lateDays * $finePerDay : 0) + $damageFine;
            
            if ($newFineStatus !== '') {
                $fineStatus = $newFineStatus;
                if ($fineAmount == 0) $fineStatus = 'none';
            } else {
                $oldFineStatus = $existing['fine_status'] ?? 'none';
                if ($oldFineStatus === 'paid' && $fineAmount > 0) {
                    $fineStatus = 'paid';
                } else {
                    $fineStatus = $fineAmount > 0 ? 'unpaid' : 'none';
                }
            }

            $stmt = $this->pdo->prepare("
                UPDATE `returns`
                SET `return_date`   = :rd,
                    `late_days`     = :ld,
                    `car_condition` = :cc,
                    `notes`         = :notes,
                    `fine_per_day`  = :fpd,
                    `fine_amount`   = :fa,
                    `fine_status`   = :fs
                WHERE `id` = :id
            ");
            $stmt->execute([
                ':rd'    => $returnDate,
                ':ld'    => $lateDays,
                ':cc'    => $carCondition,
                ':notes' => $notes !== '' ? $notes : null,
                ':fpd'   => $finePerDay,
                ':fa'    => $fineAmount,
                ':fs'    => $fineStatus,
                ':id'    => $id,
            ]);

            // Re-update status car berdasarkan kondisi terbaru
            $carStatus = ($carCondition === 'good') ? 'available' : 'maintenance';
            $this->pdo->prepare("
                UPDATE `cars`
                SET `status` = :cs, `updated_at` = NOW()
                WHERE `id` = :cid
            ")->execute([
                ':cs'  => $carStatus,
                ':cid' => $existing['car_id'],
            ]);

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    /**
     * Hapus return + kembalikan status car ke available jika kondisi good,
     * dan set booking kembali ke confirmed/ongoing tergantung tanggal.
     */
    public function deleteReturn(int $id): bool {
        $existing = $this->getReturnById($id);
        if ($existing === null) {
            return false;
        }

        $booking = $this->getBookingById($existing['booking_id']);

        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare("DELETE FROM `returns` WHERE `id` = :id")
                ->execute([':id' => $id]);

            if ($booking !== null) {
                // Kembalikan booking ke ongoing (asumsi masih aktif)
                $this->pdo->prepare("
                    UPDATE `bookings`
                    SET `status` = 'ongoing', `updated_at` = NOW()
                    WHERE `id` = :bid
                ")->execute([':bid' => $booking['id']]);

                // Kembalikan status car ke booked
                $this->pdo->prepare("
                    UPDATE `cars`
                    SET `status` = 'booked', `updated_at` = NOW()
                    WHERE `id` = :cid
                ")->execute([':cid' => $booking['car_id']]);
            }

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
