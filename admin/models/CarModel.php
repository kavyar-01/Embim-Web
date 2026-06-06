<?php
require_once __DIR__ . '/../config/database.php';

class CarModel {
    public function create(array $data): bool|string {
        try {
            $pdo  = getPDO();
            $stmt = $pdo->prepare("
                INSERT INTO `cars`
                    (`brand`, `model`, `year`, `license_plate`, `price_per_day`,
                     `transmission`, `fuel_type`, `seats`, `description`, `photo`)
                VALUES
                    (:brand, :model, :year, :license_plate, :price_per_day,
                     :transmission, :fuel_type, :seats, :description, :photo)
            ");
            $stmt->execute([
                ':brand'         => $data['brand'],
                ':model'         => $data['model'],
                ':year'          => $data['year'],
                ':license_plate' => $data['license_plate'],
                ':price_per_day' => $data['price_per_day'],
                ':transmission'  => $data['transmission'],
                ':fuel_type'     => $data['fuel_type'],
                ':seats'         => $data['seats'],
                ':description'   => $data['description'] ?: null,
                ':photo'         => $data['photo'],
            ]);
            $carId = $pdo->lastInsertId();
            
            $stmt = $pdo->prepare("
                INSERT INTO `vehicle_highlights`
                    (`car_id`, `drivetrain`, `body_style`, `engine`, `transmission`)
                VALUES
                    (:car_id, :drivetrain, :body_style, :engine, :hl_transmission)
            ");
            $stmt->execute([
                ':car_id'          => $carId,
                ':drivetrain'      => $data['hl_drivetrain'] ?? 'FWD',
                ':body_style'      => $data['hl_body_style'] ?? 'Sedan',
                ':engine'          => $data['hl_engine'] ?? null,
                ':hl_transmission' => $data['hl_transmission'] ?? 'Automatic',
            ]);
            
            return true;
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') return 'duplicate';
            throw $e;
        }
    }

    public function getAll(): array {
        return getPDO()->query("
            SELECT c.*, 
                   vh.drivetrain, vh.body_style, vh.engine, vh.transmission AS hl_transmission,
                   (SELECT COUNT(*) FROM `cars` c2 WHERE c2.brand = c.brand AND c2.model = c.model AND c2.status = 'available') AS stock
            FROM `cars` c
            LEFT JOIN `vehicle_highlights` vh ON vh.car_id = c.id
            ORDER BY c.`created_at` DESC
        ")->fetchAll();
    }

    public function findById(int $id): array|false {
        $stmt = getPDO()->prepare("
            SELECT c.*, 
                   vh.drivetrain, vh.body_style, vh.engine, vh.transmission AS hl_transmission,
                   (SELECT COUNT(*) FROM `cars` c2 WHERE c2.brand = c.brand AND c2.model = c.model AND c2.status = 'available') AS stock
            FROM `cars` c 
            LEFT JOIN `vehicle_highlights` vh ON vh.car_id = c.id 
            WHERE c.`id` = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(int $id, array $data): bool|string {
        try {
            $pdo = getPDO();
            // Jika tidak ada photo baru yang diunggah, jangan update kolom photo
            if (isset($data['photo'])) {
                $sql = "UPDATE `cars` SET 
                        `brand` = :brand, `model` = :model, `year` = :year, 
                        `license_plate` = :license_plate, `price_per_day` = :price_per_day,
                        `transmission` = :transmission, `fuel_type` = :fuel_type, 
                        `seats` = :seats, `description` = :description, `photo` = :photo,
                        `status` = :status
                        WHERE `id` = :id";
                $params = [
                    ':brand'         => $data['brand'],
                    ':model'         => $data['model'],
                    ':year'          => $data['year'],
                    ':license_plate' => $data['license_plate'],
                    ':price_per_day' => $data['price_per_day'],
                    ':transmission'  => $data['transmission'],
                    ':fuel_type'     => $data['fuel_type'],
                    ':seats'         => $data['seats'],
                    ':description'   => $data['description'] ?: null,
                    ':photo'         => $data['photo'],
                    ':status'        => $data['status'],
                    ':id'            => $id
                ];
            } else {
                $sql = "UPDATE `cars` SET 
                        `brand` = :brand, `model` = :model, `year` = :year, 
                        `license_plate` = :license_plate, `price_per_day` = :price_per_day,
                        `transmission` = :transmission, `fuel_type` = :fuel_type, 
                        `seats` = :seats, `description` = :description, `status` = :status
                        WHERE `id` = :id";
                $params = [
                    ':brand'         => $data['brand'],
                    ':model'         => $data['model'],
                    ':year'          => $data['year'],
                    ':license_plate' => $data['license_plate'],
                    ':price_per_day' => $data['price_per_day'],
                    ':transmission'  => $data['transmission'],
                    ':fuel_type'     => $data['fuel_type'],
                    ':seats'         => $data['seats'],
                    ':description'   => $data['description'] ?: null,
                    ':status'        => $data['status'],
                    ':id'            => $id
                ];
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            // Update vehicle highlights
            $stmt = $pdo->prepare("
                INSERT INTO `vehicle_highlights` (`car_id`, `drivetrain`, `body_style`, `engine`, `transmission`)
                VALUES (:car_id, :drivetrain, :body_style, :engine, :hl_transmission)
                ON DUPLICATE KEY UPDATE
                    `drivetrain` = VALUES(`drivetrain`),
                    `body_style` = VALUES(`body_style`),
                    `engine`     = VALUES(`engine`),
                    `transmission` = VALUES(`transmission`)
            ");
            $stmt->execute([
                ':car_id'          => $id,
                ':drivetrain'      => $data['hl_drivetrain'] ?? 'FWD',
                ':body_style'      => $data['hl_body_style'] ?? 'Sedan',
                ':engine'          => $data['hl_engine'] ?? null,
                ':hl_transmission' => $data['hl_transmission'] ?? 'Automatic',
            ]);

            return true;
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') return 'duplicate';
            throw $e;
        }
    }

    public function delete(int $id): bool {
        try {
            $pdo = getPDO();
            $pdo->beginTransaction();
            
            // Hapus data vehicle_highlights terlebih dahulu untuk menghindari foreign key constraint error
            $stmt = $pdo->prepare("DELETE FROM `vehicle_highlights` WHERE `car_id` = :id");
            $stmt->execute([':id' => $id]);
            
            $stmt = $pdo->prepare("DELETE FROM `cars` WHERE `id` = :id");
            $result = $stmt->execute([':id' => $id]);
            
            $pdo->commit();
            return $result;
        } catch (\PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            // Jika error karena masih ada data booking/rental yang terhubung dengan mobil ini
            if ($e->getCode() === '23000') {
                return false; 
            }
            throw $e;
        }
    }

    /**
     * Ambil semua mobil dengan filter:
     *   $search       — brand / model / license_plate
     *   $status       — available | booked | maintenance | ''
     *   $transmission — automatic | manual | ''
     *   $fuelType     — gasoline | diesel | electric | hybrid | ''
     *   $priceMin     — harga per hari minimum
     *   $priceMax     — harga per hari maksimum
     *   $yearMin      — tahun minimum
     *   $yearMax      — tahun maksimum
     */
    public function getAllFiltered(
        string $search       = '',
        string $status       = '',
        string $transmission = '',
        string $fuelType     = '',
        string $priceMin     = '',
        string $priceMax     = '',
        string $yearMin      = '',
        string $yearMax      = ''
    ): array {
        $sql = "
            SELECT c.*, 
                   vh.drivetrain, vh.body_style, vh.engine, vh.transmission AS hl_transmission,
                   (SELECT COUNT(*) FROM `cars` c2 WHERE c2.brand = c.brand AND c2.model = c.model AND c2.status = 'available') AS stock
            FROM `cars` c
            LEFT JOIN `vehicle_highlights` vh ON vh.car_id = c.id
            WHERE 1=1
        ";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (c.`brand` LIKE :search1
                          OR c.`model` LIKE :search2
                          OR c.`license_plate` LIKE :search3)";
            $params[':search1'] = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
            $params[':search3'] = '%' . $search . '%';
        }

        if ($status !== '') {
            $sql .= " AND c.`status` = :status";
            $params[':status'] = $status;
        }

        if ($transmission !== '') {
            $sql .= " AND c.`transmission` = :transmission";
            $params[':transmission'] = $transmission;
        }

        if ($fuelType !== '') {
            $sql .= " AND c.`fuel_type` = :fuel_type";
            $params[':fuel_type'] = $fuelType;
        }

        if ($priceMin !== '' && is_numeric($priceMin)) {
            $sql .= " AND c.`price_per_day` >= :price_min";
            $params[':price_min'] = (float) $priceMin;
        }

        if ($priceMax !== '' && is_numeric($priceMax)) {
            $sql .= " AND c.`price_per_day` <= :price_max";
            $params[':price_max'] = (float) $priceMax;
        }

        if ($yearMin !== '' && is_numeric($yearMin)) {
            $sql .= " AND c.`year` >= :year_min";
            $params[':year_min'] = (int) $yearMin;
        }

        if ($yearMax !== '' && is_numeric($yearMax)) {
            $sql .= " AND c.`year` <= :year_max";
            $params[':year_max'] = (int) $yearMax;
        }

        $sql .= " ORDER BY c.`created_at` DESC";

        $stmt = getPDO()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}