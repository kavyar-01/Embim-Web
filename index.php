<?php
// index.php - Front Controller
define('BASE_URL', true);

// Mulai session untuk keperluan auth
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Basic Routing
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {

    case 'home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    case 'register':
        require_once 'controllers/RegisterController.php';
        $controller = new RegisterController();
        $controller->index();
        break;

    case 'login':
        require_once 'controllers/LoginController.php';
        $controller = new LoginController();
        $controller->index();
        break;

    case 'logout':
        require_once 'controllers/LoginController.php';
        $controller = new LoginController();
        $controller->logout();
        break;

    case 'cars':
        require_once 'controllers/CarsController.php';
        $controller = new CarsController();
        $controller->index();
        break;

    case 'car-detail':
        require_once 'controllers/CarDetailController.php';
        $controller = new CarDetailController();
        $controller->index();
        break;

    case 'bookings':
        require_once 'controllers/BookingsController.php';
        $controller = new BookingsController();
        $controller->index();
        break;

    case 'receipt':
        require_once 'controllers/ReceiptController.php';
        $controller = new ReceiptController();
        $controller->download();
        break;

    case 'booking':
        require_once 'controllers/BookingController.php';
        $controller = new BookingController();
        // Step 2: upload bukti pembayaran (e-wallet & transfer bank)
        if (isset($_GET['step']) && $_GET['step'] === 'upload-proof') {
            $controller->uploadProof();
        } else {
            $controller->index();
        }
        break;

    case 'booking-success':
        require_once 'controllers/BookingController.php';
        $controller = new BookingController();
        $controller->success();
        break;

    case 'contact':
        require_once 'controllers/ContactController.php';
        $controller = new ContactController();
        $controller->index();
        break;

    case 'edit-profile':
        require_once 'controllers/EditProfileController.php';
        $controller = new EditProfileController();
        $controller->index();
        break;

    case 'review':
        require_once 'controllers/ReviewController.php';
        $controller = new ReviewController();
        $controller->store();
        break;

    case 'login-by-phone':
        require_once 'controllers/LoginController.php';
        $controller = new LoginController();
        $controller->loginByPhone();
        break;

    case 'forgot-verify':
        require_once 'controllers/ForgotPasswordController.php';
        $controller = new ForgotPasswordController();
        $controller->verify();
        break;

    case 'forgot-reset':
        require_once 'controllers/ForgotPasswordController.php';
        $controller = new ForgotPasswordController();
        $controller->reset();
        break;

    default:
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
}
?>