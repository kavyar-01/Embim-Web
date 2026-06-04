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
            return true;
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') return 'duplicate';
            throw $e;
        }
    }

    public function getAll(): array {
        return getPDO()->query("SELECT * FROM `cars` ORDER BY `created_at` DESC")->fetchAll();
    }

    public function findById(int $id): array|false {
        $stmt = getPDO()->prepare("SELECT * FROM `cars` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
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
        $sql    = "SELECT * FROM `cars` WHERE 1=1";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (`brand` LIKE :search
                          OR `model` LIKE :search
                          OR `license_plate` LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if ($status !== '') {
            $sql .= " AND `status` = :status";
            $params[':status'] = $status;
        }

        if ($transmission !== '') {
            $sql .= " AND `transmission` = :transmission";
            $params[':transmission'] = $transmission;
        }

        if ($fuelType !== '') {
            $sql .= " AND `fuel_type` = :fuel_type";
            $params[':fuel_type'] = $fuelType;
        }

        if ($priceMin !== '' && is_numeric($priceMin)) {
            $sql .= " AND `price_per_day` >= :price_min";
            $params[':price_min'] = (float) $priceMin;
        }

        if ($priceMax !== '' && is_numeric($priceMax)) {
            $sql .= " AND `price_per_day` <= :price_max";
            $params[':price_max'] = (float) $priceMax;
        }

        if ($yearMin !== '' && is_numeric($yearMin)) {
            $sql .= " AND `year` >= :year_min";
            $params[':year_min'] = (int) $yearMin;
        }

        if ($yearMax !== '' && is_numeric($yearMax)) {
            $sql .= " AND `year` <= :year_max";
            $params[':year_max'] = (int) $yearMax;
        }

        $sql .= " ORDER BY `created_at` DESC";

        $stmt = getPDO()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}