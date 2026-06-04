<?php

require_once 'models/CarModel.php';

class CarDetailController {

    public function index() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            header('Location: index.php?page=cars');
            exit;
        }

        $carModel = new CarModel();
        $car      = $carModel->getCarById($id);

        if (!$car) {
            header('Location: index.php?page=cars');
            exit;
        }

        $relatedCars = $carModel->getRelatedCars($id, 3);

        require_once 'views/user/car_detail.php';
    }
}
?>
