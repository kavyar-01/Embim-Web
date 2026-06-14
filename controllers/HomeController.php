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

        $reviewLimitInput = isset($_GET['review_limit']) ? $_GET['review_limit'] : 2;
        $reviewLimit = $reviewLimitInput === 'all' ? 'all' : (int)$reviewLimitInput;
        $allowedLimits = ['all', 2, 6, 10, 30, 50, 100];
        if (!in_array($reviewLimit, $allowedLimits, true)) {
            $reviewLimit = 2;
        }

        $reviewPage = isset($_GET['review_page']) ? (int)$_GET['review_page'] : 1;
        if ($reviewPage < 1) $reviewPage = 1;

        $actualLimit = $reviewLimit === 'all' ? 6 : (int)$reviewLimit;
        $offset = ($reviewPage - 1) * $actualLimit;
        
        $reviewRating = isset($_GET['review_rating']) ? $_GET['review_rating'] : 'all';
        $allowedRatings = ['all', '1', '2', '3', '4', '5'];
        if (!in_array($reviewRating, $allowedRatings)) {
            $reviewRating = 'all';
        }

        $reviews = $carModel->getReviews($actualLimit, $reviewRating, $offset);
        $totalReviewsForPagination = $carModel->getTotalReviews($reviewRating);
        $totalReviewPages = ceil($totalReviewsForPagination / $actualLimit);

        $totalAvailableCars = $carModel->getTotalAvailableCars();
        $totalCustomers = $userModel->getTotalCustomers();
        $averageRating = $carModel->getAverageRating();

        require_once 'views/user/home.php';
    }

    public function apiReviews() {
        header('Content-Type: application/json');
        require_once 'models/CarModel.php';
        $carModel = new CarModel();

        $limitInput = isset($_GET['limit']) ? $_GET['limit'] : 2;
        $limit = $limitInput === 'all' ? 'all' : (int)$limitInput;
        $allowedLimits = ['all', 2, 6, 10, 30, 50, 100];
        if (!in_array($limit, $allowedLimits, true)) {
            $limit = 2;
        }
        
        $page = isset($_GET['review_page']) ? (int)$_GET['review_page'] : 1;
        if ($page < 1) $page = 1;

        $actualLimit = $limit === 'all' ? 6 : (int)$limit;
        $offset = ($page - 1) * $actualLimit;

        $rating = isset($_GET['rating']) ? $_GET['rating'] : 'all';
        $allowedRatings = ['all', '1', '2', '3', '4', '5'];
        if (!in_array($rating, $allowedRatings)) {
            $rating = 'all';
        }

        $reviews = $carModel->getReviews($actualLimit, $rating, $offset);
        $totalReviewsForPagination = $carModel->getTotalReviews($rating);
        $totalReviewPages = ceil($totalReviewsForPagination / $actualLimit);
        
        // Return HTML string so we don't have to duplicate the card rendering logic in JS
        ob_start();
        if (!empty($reviews)) {
            $tDelays = ['', 'reveal-delay-2', 'reveal-delay-4'];
            foreach ($reviews as $idx => $r) {
                $delay = $tDelays[$idx % 3];
                ?>
                <div class="reveal reveal-scale visible <?php echo $delay; ?> bg-white p-8 rounded-2xl border border-blue-100/50 hover:shadow-lg hover:shadow-blue-200/30 transition-all duration-300">
                    <div class="flex text-yellow-500 mb-4">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <svg class="h-5 w-5 <?php echo $i <= $r['rating'] ? 'fill-current' : 'fill-gray-200'; ?>" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <?php endfor; ?>
                    </div>
                    <p class="text-gray-700 italic mb-6 font-medium leading-relaxed">
                        "<?php echo htmlspecialchars($r['comment']); ?>"
                    </p>
                    <div class="border-t border-blue-100 pt-4">
                        <h4 class="font-bold text-gray-900"><?php echo htmlspecialchars($r['full_name']); ?></h4>
                        <p class="text-sm text-blue-600 font-bold uppercase tracking-wide"><?php echo htmlspecialchars($r['car_name']); ?></p>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="text-center py-16 text-gray-400 font-medium col-span-full">
                No reviews from customers yet.
            </div>
            <?php
        }
        $html = ob_get_clean();

        $paginationHtml = '';
        if ($limit === 'all' && $totalReviewPages > 1) {
            ob_start();
            ?>
            <div class="col-span-full flex justify-center mt-8">
                <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
                    <?php if ($page > 1): ?>
                        <button onclick="fetchReviews(<?php echo $page - 1; ?>)" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            Previous
                        </button>
                    <?php else: ?>
                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                            Previous
                        </span>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalReviewPages; $i++): ?>
                        <button onclick="fetchReviews(<?php echo $i; ?>)" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo $i === $page ? 'text-blue-600 bg-blue-50 font-bold' : 'text-gray-700 hover:bg-gray-50'; ?>">
                            <?php echo $i; ?>
                        </button>
                    <?php endfor; ?>

                    <?php if ($page < $totalReviewPages): ?>
                        <button onclick="fetchReviews(<?php echo $page + 1; ?>)" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            Next
                        </button>
                    <?php else: ?>
                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                            Next
                        </span>
                    <?php endif; ?>
                </nav>
            </div>
            <?php
            $paginationHtml = ob_get_clean();
        }

        echo json_encode(['success' => true, 'html' => $html, 'paginationHtml' => $paginationHtml]);
        exit;
    }
}
?>