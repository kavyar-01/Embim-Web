<?php

require_once 'config/database.php';

class CarModel {
    private $conn;
    private $table = 'cars';

    public function __construct() {
        $db         = new Database();
        $this->conn = $db->getConnection();
    }

    public function getTotalAvailableCars() {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE status = 'available'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getAverageRating() {
        $sql = "SELECT AVG(rating) FROM reviews";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $avg = $stmt->fetchColumn();
        return $avg ? round((float)$avg, 1) : 0;
    }

    public function getFeaturedCars($limit = null) {
        $sql = "SELECT c.*, 
                       (SELECT COUNT(*) FROM {$this->table} c2 WHERE c2.brand = c.brand AND c2.model = c.model AND c2.status = 'available') as stock
                FROM {$this->table} c
                WHERE c.status = 'available'
                  AND c.id IN (SELECT MIN(id) FROM {$this->table} WHERE status = 'available' GROUP BY brand, model)
                ORDER BY c.id DESC";
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
        $sql = "SELECT c.*, 
                       (SELECT COUNT(*) FROM {$this->table} c2 WHERE c2.brand = c.brand AND c2.model = c.model AND c2.status = 'available') as stock
                FROM {$this->table} c
                WHERE c.status = 'available'
                  AND (
                      c.brand        LIKE :kw
                   OR c.model        LIKE :kw2
                   OR c.transmission LIKE :kw3
                   OR c.fuel_type    LIKE :kw4
                   OR c.description  LIKE :kw5
                  )
                  AND c.id IN (SELECT MIN(id) FROM {$this->table} WHERE status = 'available' GROUP BY brand, model)
                ORDER BY c.id DESC";
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
        $sql  = "SELECT c.*, v.drivetrain, v.body_style, v.engine, v.transmission AS hl_transmission,
                        (SELECT COUNT(*) FROM {$this->table} c2 WHERE c2.brand = c.brand AND c2.model = c.model AND c2.status = 'available') as stock
                 FROM {$this->table} c
                 LEFT JOIN vehicle_highlights v ON c.id = v.car_id
                 WHERE c.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }


    public function getAllCars() {
        $sql  = "SELECT c.*, 
                        (SELECT COUNT(*) FROM {$this->table} c2 WHERE c2.brand = c.brand AND c2.model = c.model AND c2.status = 'available') as stock
                 FROM {$this->table} c 
                 WHERE c.id IN (SELECT MIN(id) FROM {$this->table} GROUP BY brand, model)
                 ORDER BY c.id DESC";
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

    public function getTotalReviews() {
        $sql = "SELECT COUNT(*) FROM reviews";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }


    public function getRelatedCars($excludeId, $limit = 3) {
        $sql  = "SELECT c.*, 
                        (SELECT COUNT(*) FROM {$this->table} c2 WHERE c2.brand = c.brand AND c2.model = c.model AND c2.status = 'available') as stock
                 FROM {$this->table} c
                 WHERE c.status = 'available' AND c.id != :id
                   AND c.id IN (SELECT MIN(id) FROM {$this->table} WHERE status = 'available' GROUP BY brand, model)
                 ORDER BY RAND() LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id',    $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit,     PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function getCarsWithFilter($search = '', $transmission = '', $fuel_type = '', $limit = 6, $offset = 0, $price_min = '', $price_max = '', $seats = '') {
        $conditions = ["c.status = 'available'"];
        $params     = [];

        if (!empty($search)) {
            $conditions[] = "(c.brand LIKE :search OR c.model LIKE :search2 OR c.description LIKE :search3)";
            $kw = '%' . $search . '%';
            $params[':search']  = $kw;
            $params[':search2'] = $kw;
            $params[':search3'] = $kw;
        }

        if (!empty($transmission)) {
            $conditions[] = "c.transmission = :transmission";
            $params[':transmission'] = $transmission;
        }

        if (!empty($fuel_type)) {
            $conditions[] = "c.fuel_type = :fuel_type";
            $params[':fuel_type'] = $fuel_type;
        }

        if ($price_min !== '' && is_numeric($price_min)) {
            $conditions[] = "c.price_per_day >= :price_min";
            $params[':price_min'] = (int) $price_min;
        }

        if ($price_max !== '' && is_numeric($price_max)) {
            $conditions[] = "c.price_per_day <= :price_max";
            $params[':price_max'] = (int) $price_max;
        }

        if (!empty($seats) && is_numeric($seats)) {
            $conditions[] = "c.seats = :seats";
            $params[':seats'] = (int) $seats;
        }

        $where = implode(' AND ', $conditions);

        $countSql  = "SELECT COUNT(DISTINCT c.brand, c.model) FROM {$this->table} c WHERE {$where}";
        $countStmt = $this->conn->prepare($countSql);
        foreach ($params as $k => $v) $countStmt->bindValue($k, $v);
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        $sql  = "SELECT c.*,
                        (SELECT COUNT(*) FROM {$this->table} c2 WHERE c2.brand = c.brand AND c2.model = c.model AND c2.status = 'available') as stock
                 FROM {$this->table} c 
                 WHERE {$where} 
                   AND c.id IN (SELECT MIN(id) FROM {$this->table} WHERE status = 'available' GROUP BY brand, model)
                 ORDER BY c.id DESC LIMIT :limit OFFSET :offset";
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