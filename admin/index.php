<?php
    declare(strict_types=1);
    session_start();

    require_once __DIR__ . '/config/database.php';
    require_once __DIR__ . '/controllers/AuthController.php';
    require_once __DIR__ . '/controllers/DashboardController.php';
    require_once __DIR__ . '/controllers/UserController.php';
    require_once __DIR__ . '/controllers/AdminReturnController.php';
    require_once __DIR__ . '/controllers/AdminBookingController.php';
    require_once __DIR__ . '/controllers/AdminReviewController.php';
    require_once __DIR__ . '/controllers/ProfileController.php';

    $page = $_GET['page'] ?? 'dashboard';

    // Public auth routes
    if (in_array($page, ['login', 'register', 'logout', 'forgot-verify', 'forgot-reset'], true)) {
        match ($page) {
            'login'         => (new AuthController())->login(),
            'register'      => (new AuthController())->register(),
            'logout'        => (new AuthController())->logout(),
            'forgot-verify' => (new AuthController())->forgotVerify(),
            'forgot-reset'  => (new AuthController())->forgotReset(),
        };
        exit;
    }

    // Guard: redirect to login if not authenticated
    if (empty($_SESSION['admin_id'])) {
        header('Location: ?page=login');
        exit;
    }

    // Placeholder pages (no CRUD yet)
    $placeholderPages = [];
    if (in_array($page, $placeholderPages, true)) {
        require_once __DIR__ . '/views/placeholder.php';
        exit;
    }

    match ($page) {
        'add_car'               => (new DashboardController())->addCar(),
        'edit_car'              => (new DashboardController())->editCar(),
        'delete_car'            => (new DashboardController())->deleteCar(),
        'manage_cars'           => (new DashboardController())->manageCars(),
        'manage_bookings'       => (new AdminBookingController())->manageBookings(),
        'manage_payments'       => (new DashboardController())->managePayments(),
        'export_payments'       => (new DashboardController())->exportPayments(),
        'payment_detail'        => (new DashboardController())->paymentDetail(),
        'edit_payment'          => (new DashboardController())->editPayment(),
        'booking_detail'        => (new AdminBookingController())->bookingDetail(),
        'edit_booking'          => (new AdminBookingController())->editBooking(),
        'delete_booking'        => (new AdminBookingController())->deleteBooking(),
        'manage_users'          => (new UserController())->manageUsers(),
        'manage_returns'        => (new AdminReturnController())->manageReturns(),
        'add_return'            => (new AdminReturnController())->addReturn(),
        'return_detail'         => (new AdminReturnController())->returnDetail(),
        'edit_return'           => (new AdminReturnController())->editReturn(),
        'delete_return'         => (new AdminReturnController())->deleteReturn(),
        'manage_reviews'        => (new AdminReviewController())->manageReviews(),
        'delete_review'         => (new AdminReviewController())->deleteReview(),
        'edit_profile'          => (new ProfileController())->editProfile(),
        'api_check_bookings'    => (new AdminBookingController())->checkNewBookings(),
        'api_check_cancelled_bookings' => (new AdminBookingController())->checkCancelledBookings(),
        default                 => (new DashboardController())->dashboard(),
    };
