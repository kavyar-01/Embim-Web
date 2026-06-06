<?php

require_once 'models/CarModel.php';
require_once 'models/UserModel.php';

class HomeController {
    public function index() {
        $carModel = new CarModel();
        $userModel = new UserModel();

        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

        if (!empty($searchQuery)) {
            $featuredCars = $carModel->searchCars($searchQuery);
        } else {
            // Ambil maksimal 6 kendaraan available dari database
            $featuredCars = $carModel->getFeaturedCars(6);
        }

        $reviewLimit = isset($_GET['review_limit']) ? (int)$_GET['review_limit'] : 6;
        $allowedLimits = [6, 10, 30, 50, 100];
        if (!in_array($reviewLimit, $allowedLimits)) {
            $reviewLimit = 6;
        }

        $reviews = $carModel->getReviews($reviewLimit);
        $totalReviews = $carModel->getTotalReviews();

        $totalAvailableCars = $carModel->getTotalAvailableCars();
        $totalCustomers = $userModel->getTotalCustomers();
        $averageRating = $carModel->getAverageRating();

        require_once 'views/user/home.php';
    }
}
?>