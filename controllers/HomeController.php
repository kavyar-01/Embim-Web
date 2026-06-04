<?php

require_once 'models/CarModel.php';

class HomeController {
    public function index() {
        $carModel = new CarModel();

        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

        if (!empty($searchQuery)) {
            $featuredCars = $carModel->searchCars($searchQuery);
        } else {
            // Ambil maksimal 6 kendaraan available dari database
            $featuredCars = $carModel->getFeaturedCars(6);
        }

        $reviews = $carModel->getReviews(6);

        require_once 'views/user/home.php';
    }
}
?>