<?php

require_once 'models/CarModel.php';

class CarsController {

    private $perPage = 8;

    public function index() {
        $carModel = new CarModel();

        $search       = trim($_GET['search']       ?? '');
        $transmission = trim($_GET['transmission'] ?? '');
        $fuel_type    = trim($_GET['fuel_type']    ?? '');
        $price_min    = trim($_GET['price_min']    ?? '');
        $price_max    = trim($_GET['price_max']    ?? '');
        $seats        = trim($_GET['seats']        ?? '');
        $page         = max(1, (int)($_GET['p']    ?? 1));
        $offset       = ($page - 1) * $this->perPage;

        $result     = $carModel->getCarsWithFilter($search, $transmission, $fuel_type, $this->perPage, $offset, $price_min, $price_max, $seats);
        $cars       = $result['cars'];
        $totalCars  = $result['total'];
        $totalPages = (int) ceil($totalCars / $this->perPage);

        $availableSeats = $carModel->getAvailableSeats();

        require_once 'views/user/cars.php';
    }
}
?>