<?php
    declare(strict_types=1);
    session_start();

    require_once __DIR__ . '/config/database.php';
    require_once __DIR__ . '/controllers/AuthController.php';
    require_once __DIR__ . '/controllers/DashboardController.php';
    require_once __DIR__ . '/controllers/UserController.php';
    require_once __DIR__ . '/controllers/AdminPaymentController.php';
    require_once __DIR__ . '/controllers/AdminReturnController.php';
    require_once __DIR__ . '/controllers/AdminFineController.php';
    require_once __DIR__ . '/controllers/AdminBookingController.php';

    $page = $_GET['page'] ?? 'dashboard';

    // Public auth routes
    if (in_array($page, ['login', 'register', 'logout'], true)) {
        match ($page) {
            'login'    => (new AuthController())->login(),
            'register' => (new AuthController())->register(),
            'logout'   => (new AuthController())->logout(),
        };
        exit;
    }

    // Guard: redirect to login if not authenticated
    if (empty($_SESSION['admin_id'])) {
        header('Location: ?page=login');
        exit;
    }

    // Placeholder pages (no CRUD yet)
    $placeholderPages = ['manage_reviews'];
    if (in_array($page, $placeholderPages, true)) {
        require_once __DIR__ . '/views/placeholder.php';
        exit;
    }

    match ($page) {
        'add_car'               => (new DashboardController())->addCar(),
        'manage_cars'           => (new DashboardController())->manageCars(),
        'manage_bookings'       => (new AdminBookingController())->manageBookings(),
        'booking_detail'        => (new AdminBookingController())->bookingDetail(),
        'edit_booking'          => (new AdminBookingController())->editBooking(),
        'delete_booking'        => (new AdminBookingController())->deleteBooking(),
        'manage_users'          => (new UserController())->manageUsers(),
        'manage_payments'       => (new AdminPaymentController())->managePayments(),
        'payment_detail'        => (new AdminPaymentController())->paymentDetail(),
        'update_payment_status' => (new AdminPaymentController())->updateStatus(),
        'manage_returns'        => (new AdminReturnController())->manageReturns(),
        'add_return'            => (new AdminReturnController())->addReturn(),
        'return_detail'         => (new AdminReturnController())->returnDetail(),
        'edit_return'           => (new AdminReturnController())->editReturn(),
        'delete_return'         => (new AdminReturnController())->deleteReturn(),
        'manage_fines'          => (new AdminFineController())->manageFines(),
        'fine_detail'           => (new AdminFineController())->fineDetail(),
        'update_fine_status'    => (new AdminFineController())->updateFineStatus(),
        default                 => (new DashboardController())->dashboard(),
    };
