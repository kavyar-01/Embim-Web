<?php

require_once 'config/database.php';

class CarModel {
    private $conn;
    private $table = 'cars';

    public function __construct() {
        $db         = new Database();
        $this->conn = $db->getConnection();
    }


    public function getFeaturedCars($limit = null) {
        $sql  = "SELECT * FROM {$this->table} WHERE status = 'available' ORDER BY id DESC";
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }
        $stmt = $this->conn->prepare($sql);
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function searchCars($query) {
        $keyword = '%' . $query . '%';
        $sql = "SELECT * FROM {$this->table}
                WHERE status = 'available'
                  AND (
                      brand        LIKE :kw
                   OR model        LIKE :kw2
                   OR transmission LIKE :kw3
                   OR fuel_type    LIKE :kw4
                   OR description  LIKE :kw5
                  )
                ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':kw',  $keyword);
        $stmt->bindParam(':kw2', $keyword);
        $stmt->bindParam(':kw3', $keyword);
        $stmt->bindParam(':kw4', $keyword);
        $stmt->bindParam(':kw5', $keyword);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function getCarById($id) {
        $sql  = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }


    public function getAllCars() {
        $sql  = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function updateStatus($id, $status) {
        $sql  = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id',     $id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function getReviews($limit = 6) {
        $sql = "SELECT r.id, r.rating, r.comment, r.created_at,
                       u.full_name,
                       CONCAT(c.brand, ' ', c.model) AS car_name
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                JOIN cars  c ON r.car_id  = c.id
                ORDER BY r.created_at DESC
                LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function getRelatedCars($excludeId, $limit = 3) {
        $sql  = "SELECT * FROM {$this->table}
                 WHERE status = 'available' AND id != :id
                 ORDER BY RAND() LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id',    $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit,     PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function getCarsWithFilter($search = '', $transmission = '', $fuel_type = '', $limit = 6, $offset = 0, $price_min = '', $price_max = '', $seats = '') {
        $conditions = ["status = 'available'"];
        $params     = [];

        if (!empty($search)) {
            $conditions[] = "(brand LIKE :search OR model LIKE :search2 OR description LIKE :search3)";
            $kw = '%' . $search . '%';
            $params[':search']  = $kw;
            $params[':search2'] = $kw;
            $params[':search3'] = $kw;
        }

        if (!empty($transmission)) {
            $conditions[] = "transmission = :transmission";
            $params[':transmission'] = $transmission;
        }

        if (!empty($fuel_type)) {
            $conditions[] = "fuel_type = :fuel_type";
            $params[':fuel_type'] = $fuel_type;
        }

        if ($price_min !== '' && is_numeric($price_min)) {
            $conditions[] = "price_per_day >= :price_min";
            $params[':price_min'] = (int) $price_min;
        }

        if ($price_max !== '' && is_numeric($price_max)) {
            $conditions[] = "price_per_day <= :price_max";
            $params[':price_max'] = (int) $price_max;
        }

        if (!empty($seats) && is_numeric($seats)) {
            $conditions[] = "seats = :seats";
            $params[':seats'] = (int) $seats;
        }

        $where = implode(' AND ', $conditions);

        $countSql  = "SELECT COUNT(*) FROM {$this->table} WHERE {$where}";
        $countStmt = $this->conn->prepare($countSql);
        foreach ($params as $k => $v) $countStmt->bindValue($k, $v);
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        $sql  = "SELECT * FROM {$this->table} WHERE {$where} ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return ['cars' => $stmt->fetchAll(), 'total' => $total];
    }


    public function getAvailableSeats() {
        $sql  = "SELECT DISTINCT seats FROM {$this->table} WHERE status = 'available' ORDER BY seats ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>