<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class BookingModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    /**
     * Ambil satu booking lengkap dengan info user dan car.
     */
    public function getBookingById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                b.`id`, b.`user_id`, b.`car_id`,
                b.`start_date`, b.`end_date`, b.`total_days`,
                b.`total_price`, b.`status`, b.`notes`,
                b.`created_at`, b.`updated_at`,
                u.`full_name`  AS customer_name,
                u.`email`      AS customer_email,
                u.`phone`      AS customer_phone,
                CONCAT(c.`brand`, ' ', c.`model`) AS car_name,
                c.`license_plate`,
                c.`price_per_day`
            FROM `bookings` b
            JOIN `users` u ON u.`id` = b.`user_id`
            JOIN `cars`  c ON c.`id` = b.`car_id`
            WHERE b.`id` = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Update status dan notes booking.
     */
    public function updateBooking(int $id, string $status, string $notes): bool
    {
        $allowed = ['confirmed', 'ongoing', 'completed', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            UPDATE `bookings`
            SET `status`     = :status,
                `notes`      = :notes,
                `updated_at` = NOW()
            WHERE `id` = :id
        ");
        return $stmt->execute([
            ':status' => $status,
            ':notes'  => $notes !== '' ? $notes : null,
            ':id'     => $id,
        ]);
    }

    /**
     * Hapus booking. Jika ada return terkait, ON DELETE CASCADE akan
     * menghapus returns otomatis (beserta data denda di dalamnya).
     * Kembalikan status car ke available jika belum ada booking aktif lain.
     */
    public function deleteBooking(int $id): bool
    {
        $booking = $this->getBookingById($id);
        if ($booking === null) {
            return false;
        }

        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare("DELETE FROM `bookings` WHERE `id` = :id")
                ->execute([':id' => $id]);

            // Kembalikan status car ke available jika tidak ada booking aktif lain
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM `bookings`
                WHERE `car_id` = :cid
                  AND `status` IN ('confirmed','ongoing')
                  AND `id` != :bid
            ");
            $stmt->execute([':cid' => $booking['car_id'], ':bid' => $id]);
            $activeCount = (int) $stmt->fetchColumn();

            if ($activeCount === 0) {
                $this->pdo->prepare("
                    UPDATE `cars`
                    SET `status` = 'available', `updated_at` = NOW()
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
