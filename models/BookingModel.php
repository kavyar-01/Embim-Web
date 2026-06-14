<?php

require_once 'config/database.php';

class BookingModel {
    private $conn;

    public function __construct() {
        $db         = new Database();
        $this->conn = $db->getConnection();
    }

    public function getByUserId($userId, $status = 'all', $search = '', $limit = null, $offset = 0) {
        $sql = "SELECT
                    b.id,
                    b.car_id,
                    b.start_date,
                    b.end_date,
                    b.total_days,
                    b.total_price,
                    b.status,
                    b.notes,
                    b.created_at,
                    c.brand       AS car_brand,
                    c.model       AS car_model,
                    c.year        AS car_year,
                    c.photo       AS car_photo,
                    c.price_per_day,
                    b.payment_method,
                    b.payment_status,
                    CASE WHEN r.id IS NOT NULL THEN 1 ELSE 0 END AS has_review,
                    r.rating      AS review_rating,
                    ret.fine_status,
                    ret.fine_amount
                FROM bookings b
                JOIN cars     c ON b.car_id     = c.id
                LEFT JOIN reviews  r ON r.booking_id = b.id
                LEFT JOIN returns  ret ON ret.booking_id = b.id
                WHERE b.user_id = :user_id";

        if ($status !== 'all') {
            $sql .= " AND b.status = :status";
        }

        if (!empty($search)) {
            $sql .= " AND (b.id LIKE :search1 OR LPAD(b.id, 4, '0') LIKE :search4 OR c.brand LIKE :search2 OR c.model LIKE :search3)";
        }

        $sql .= " ORDER BY b.created_at DESC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        if ($status !== 'all') {
            $stmt->bindValue(':status', $status);
        }
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $searchIdTerm = '%' . str_replace('#', '', $search) . '%';
            $stmt->bindValue(':search1', $searchTerm);
            $stmt->bindValue(':search4', $searchIdTerm);
            $stmt->bindValue(':search2', $searchTerm);
            $stmt->bindValue(':search3', $searchTerm);
        }
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalBookingsByUserId($userId, $status = 'all', $search = '') {
        $sql = "SELECT COUNT(*) FROM bookings b JOIN cars c ON b.car_id = c.id WHERE b.user_id = :user_id";
        
        if ($status !== 'all') {
            $sql .= " AND b.status = :status";
        }

        if (!empty($search)) {
            $sql .= " AND (b.id LIKE :search1 OR LPAD(b.id, 4, '0') LIKE :search4 OR c.brand LIKE :search2 OR c.model LIKE :search3)";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        if ($status !== 'all') {
            $stmt->bindValue(':status', $status);
        }
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $searchIdTerm = '%' . str_replace('#', '', $search) . '%';
            $stmt->bindValue(':search1', $searchTerm);
            $stmt->bindValue(':search4', $searchIdTerm);
            $stmt->bindValue(':search2', $searchTerm);
            $stmt->bindValue(':search3', $searchTerm);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getCountsByUserId($userId) {
        $sql = "SELECT status, COUNT(*) AS total
                FROM bookings
                WHERE user_id = :user_id
                GROUP BY status";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $counts = [
            'all'       => 0,
            'confirmed' => 0,
            'ongoing'   => 0,
            'completed' => 0,
            'cancelled' => 0,
        ];

        foreach ($rows as $row) {
            if (isset($counts[$row['status']])) {
                $counts[$row['status']] = (int) $row['total'];
                $counts['all'] += (int) $row['total'];
            }
        }

        return $counts;
    }


    public function createBooking($data) {
        $method = $data['payment_method'] ?? '';
        $bookingStatus = 'confirmed';

        $this->conn->beginTransaction();
        try {
            if (!$this->isCarAvailable($data['car_id'], $data['start_date'], $data['end_date'], true)) {
                $this->conn->rollBack();
                return false;
            }

            $sql = "INSERT INTO bookings
                        (user_id, car_id, start_date, end_date, total_days, total_price, status, notes, payment_method, payment_status)
                    VALUES
                        (:user_id, :car_id, :start_date, :end_date, :total_days, :total_price, :status, :notes, :payment_method, 'unpaid')";
            $stmt = $this->conn->prepare($sql);
            $success = $stmt->execute([
                ':user_id'        => $data['user_id'],
                ':car_id'         => $data['car_id'],
                ':start_date'     => $data['start_date'],
                ':end_date'       => $data['end_date'],
                ':total_days'     => $data['total_days'],
                ':total_price'    => $data['total_price'],
                ':status'         => $bookingStatus,
                ':notes'          => $data['notes'] ?? null,
                ':payment_method' => $method,
            ]);

            if (!$success) {
                $this->conn->rollBack();
                return false;
            }

            $bookingId = $this->conn->lastInsertId();
            $this->updateCarStatus($data['car_id'], $bookingStatus);
            $this->conn->commit();
            return $bookingId;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return false;
        }
    }

 
    public function saveIdentityPhoto($bookingId, $filename) {
        $sql  = "UPDATE bookings SET identity_photo = :photo WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':photo' => $filename, ':id' => $bookingId]);
    }


    public function isCarAvailable($carId, $startDate = null, $endDate = null, $forUpdate = false) {
        $lock = $forUpdate ? " FOR UPDATE" : "";
        $stmt = $this->conn->prepare("SELECT status FROM cars WHERE id = :id" . $lock);
        $stmt->execute([':id' => $carId]);
        $status = $stmt->fetchColumn();

        if ($status === false || $status === 'maintenance') {
            return false;
        }

        if (!$startDate || !$endDate) {
            return $status === 'available';
        }

        $stmt = $this->conn->prepare("
            SELECT COUNT(*)
            FROM bookings
            WHERE car_id = :car_id
              AND status IN ('pending', 'confirmed', 'ongoing')
              AND start_date < :end_date
              AND end_date > :start_date
        ");
        $stmt->execute([
            ':car_id'     => $carId,
            ':start_date' => $startDate,
            ':end_date'   => $endDate,
        ]);

        return (int)$stmt->fetchColumn() === 0;
    }


    public function uploadPaymentProof($bookingId, $filename) {
        $sql  = "UPDATE bookings
                 SET payment_proof  = :proof,
                     payment_status = 'paid',
                     paid_at        = NOW()
                 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':proof' => $filename, ':id' => $bookingId]);
    }


    public function getBookingById($bookingId) {
        $sql = "SELECT b.*, c.brand, c.model, c.year, c.photo, c.price_per_day
                FROM bookings b
                JOIN cars c ON b.car_id = c.id
                WHERE b.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $bookingId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }


    public function createReview($data) {
        $sql = "INSERT INTO reviews (user_id, car_id, booking_id, rating, comment)
                VALUES (:user_id, :car_id, :booking_id, :rating, :comment)";
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':user_id'    => $data['user_id'],
                ':car_id'     => $data['car_id'],
                ':booking_id' => $data['booking_id'],
                ':rating'     => $data['rating'],
                ':comment'    => $data['comment'],
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }


    public function hasReview($bookingId) {
        $sql  = "SELECT COUNT(*) FROM reviews WHERE booking_id = :booking_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':booking_id' => $bookingId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function updateCarStatus($carId, $bookingStatus) {
        if (in_array($bookingStatus, ['completed', 'cancelled'])) {
            $stmt = $this->conn->prepare("
                SELECT COUNT(*)
                FROM bookings
                WHERE car_id = :car_id
                  AND status IN ('confirmed', 'ongoing')
            ");
            $stmt->execute([':car_id' => $carId]);
            $carStatus = ((int)$stmt->fetchColumn() > 0) ? 'booked' : 'available';
        } else {
            $carStatus = 'booked';
        }

        $sql = "UPDATE cars SET status = :status WHERE id = :car_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':status' => $carStatus,
            ':car_id' => $carId
        ]);
    }


    public function updateBookingStatus($bookingId, $newStatus) {
        $booking = $this->getBookingById($bookingId);
        if (!$booking) return false;

        $oldStatus = $booking['status'];

        $sql = "UPDATE bookings SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([
            ':status' => $newStatus,
            ':id'     => $bookingId
        ]);

        if ($success) {
            $this->updateCarStatus($booking['car_id'], $newStatus);
            return true;
        }
        return false;
    }


    public function resetUnbookedCarsStatus() {
        $sql = "UPDATE cars 
                SET status = 'available' 
                WHERE id NOT IN (
                    SELECT DISTINCT car_id FROM bookings 
                    WHERE status IN ('confirmed', 'ongoing')
                )";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute();
    }

    public function hasActiveBooking($userId) {
        $sql = "SELECT COUNT(*) FROM bookings 
                WHERE user_id = :user_id 
                  AND status NOT IN ('completed', 'cancelled')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Cek apakah user memiliki denda yang belum dibayar.
     */
    public function hasUnpaidFines($userId) {
        $sql = "SELECT COUNT(*) FROM returns r
                JOIN bookings b ON b.id = r.booking_id
                WHERE b.user_id = :user_id
                  AND r.fine_status = 'unpaid'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Ambil ID booking refund yang belum pernah dinotifikasi ke user.
     */
    public function getRecentRefunds($userId) {
        $sql = "SELECT id FROM bookings 
                WHERE user_id = :user_id 
                  AND payment_status = 'refunded' 
                  AND refund_notified_at IS NULL
                ORDER BY updated_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function markRefundsNotified($userId, array $bookingIds) {
        $bookingIds = array_values(array_filter(array_map('intval', $bookingIds)));
        if (empty($bookingIds)) {
            return true;
        }

        $placeholders = implode(',', array_fill(0, count($bookingIds), '?'));
        $sql = "UPDATE bookings
                SET refund_notified_at = NOW()
                WHERE user_id = ?
                  AND payment_status = 'refunded'
                  AND refund_notified_at IS NULL
                  AND id IN ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(array_merge([(int)$userId], $bookingIds));
    }
}
?>
