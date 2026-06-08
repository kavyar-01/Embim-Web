<?php

require_once 'models/CarModel.php';
require_once 'models/BookingModel.php';

class BookingController {

    private $uploadDir        = 'assets/images/booking/';
    private $proofUploadDir   = 'assets/images/payment_proof/';
    private $allowedPaymentMethods = [
        'gopay',
        'ovo',
        'dana',
        'shopeepay',
        'transfer_bank_bca',
        'transfer_bank_mandiri',
        'transfer_bank_bni',
        'transfer_bank_bri',
        'transfer_bank_seabank',
    ];


    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: index.php?page=login');
            exit;
        }

        $carModel     = new CarModel();
        $bookingModel = new BookingModel();
        $errors       = [];

        $carId     = (int)(  isset($_POST['car_id'])     ? $_POST['car_id']     : (isset($_GET['car_id'])     ? $_GET['car_id']     : 0));
        $startDate = trim(   isset($_POST['start_date'])  ? $_POST['start_date']  : (isset($_GET['start_date'])  ? $_GET['start_date']  : ''));
        $endDate   = trim(   isset($_POST['end_date'])    ? $_POST['end_date']    : (isset($_GET['end_date'])    ? $_GET['end_date']    : ''));

        if (!$carId) {
            header('Location: index.php?page=cars');
            exit;
        }

        $car = $carModel->getCarById($carId);
        if (!$car || $car['status'] !== 'available') {
            header('Location: index.php?page=cars');
            exit;
        }

        if ($bookingModel->hasActiveBooking($_SESSION['user_id'])) {
            $_SESSION['booking_error'] = 'You still have active bookings. Please complete them first before making a new booking.';
            header('Location: index.php?page=bookings');
            exit;
        }

        if ($bookingModel->hasUnpaidFines($_SESSION['user_id'])) {
            $_SESSION['booking_error'] = 'You have unpaid fines. Please settle your fines first before making a new booking.';
            header('Location: index.php?page=bookings');
            exit;
        }

        $totalDays  = 0;
        $totalPrice = 0;
        $start      = $this->parseBookingDate($startDate);
        $end        = $this->parseBookingDate($endDate);

        if ($startDate && $endDate) {
            if ($start && $end && $end > $start) {
                $diff = $start->diff($end)->days;
                $totalDays  = $diff;
                $totalPrice = $diff * (int)$car['price_per_day'];
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $notes  = trim($_POST['notes']          ?? '');
            $method = trim($_POST['payment_method'] ?? '');

            if (!$carId)           $errors[] = 'Invalid vehicle data.';
            if (empty($startDate)) $errors[] = 'Pickup date is required.';
            if (empty($endDate))   $errors[] = 'Return date is required.';
            if (empty($method))    $errors[] = 'Payment method must be selected.';
            if ($method !== '' && !in_array($method, $this->allowedPaymentMethods, true)) {
                $errors[] = 'Invalid payment method selected.';
            }

            if ($startDate && $endDate) {
                if (!$start || !$end) {
                    $errors[] = 'Invalid rental date format.';
                } else {
                    $today = new DateTime('today');

                    if ($start < $today)  $errors[] = 'Pickup date cannot be in the past.';
                    if ($end <= $start)   $errors[] = 'Return date must be after pickup date.';

                    if ($end > $start) {
                        $totalDays  = $start->diff($end)->days;
                        $totalPrice = $totalDays * (int)$car['price_per_day'];
                    }
                }
            }

            $selfieFilename = null;
            if (empty($errors)) {
                if (!$bookingModel->isCarAvailable($carId, $startDate, $endDate)) {
                    $errors[] = 'The vehicle is not available on the selected date.';
                }
            }

            if (empty($errors)) {
                if (empty($_FILES['selfie_ktp']['name'])) {
                    $errors[] = 'Selfie with ID card must be uploaded.';
                } else {
                    $upload = $this->handleUpload('selfie_ktp', $this->uploadDir);
                    if ($upload['error']) {
                        $errors[] = $upload['error'];
                    } else {
                        $selfieFilename = $upload['filename'];
                    }
                }
            }

            if (empty($errors)) {
                $_SESSION['pending_booking'] = [
                    'user_id'        => $_SESSION['user_id'],
                    'car_id'         => $carId,
                    'start_date'     => $startDate,
                    'end_date'       => $endDate,
                    'total_days'     => $totalDays,
                    'total_price'    => $totalPrice,
                    'notes'          => $notes,
                    'payment_method' => $method,
                    'selfie_filename'=> $selfieFilename,
                    'car'            => $car // To display car details on payment_proof page
                ];

                header('Location: index.php?page=booking&step=upload-proof');
                exit;
            }
        }

        require_once 'views/user/booking.php';
    }


    public function uploadProof() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if (!isset($_SESSION['pending_booking'])) {
            header('Location: index.php?page=bookings');
            exit;
        }

        $booking = $_SESSION['pending_booking'];
        
        $errors  = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingModel = new BookingModel();
            if ((int)$booking['user_id'] !== (int)$_SESSION['user_id']) {
                unset($_SESSION['pending_booking']);
                header('Location: index.php?page=bookings');
                exit;
            }

            $start = $this->parseBookingDate($booking['start_date'] ?? '');
            $end   = $this->parseBookingDate($booking['end_date'] ?? '');
            if (!$start || !$end || $end <= $start) {
                $errors[] = 'Invalid rental date data. Please start the booking again.';
            }

            $hasActiveBooking = $bookingModel->hasActiveBooking($_SESSION['user_id']);
            $hasUnpaidFines   = $bookingModel->hasUnpaidFines($_SESSION['user_id']);

            if ($hasActiveBooking) {
                $errors[] = 'You still have active bookings. Please complete them first before making a new booking.';
            }
            if ($hasUnpaidFines) {
                $errors[] = 'You have unpaid fines. Please settle your fines first before making a new booking.';
            }
            if (!$hasActiveBooking
                && !$hasUnpaidFines
                && !$bookingModel->isCarAvailable($booking['car_id'], $booking['start_date'], $booking['end_date'])) {
                $errors[] = 'The vehicle is not available on the selected date.';
            }

            if (empty($errors) && empty($_FILES['payment_proof']['name'])) {
                $errors[] = 'Payment proof must be uploaded.';
            } elseif (empty($errors)) {
                $upload = $this->handleUpload('payment_proof', $this->proofUploadDir);
                if ($upload['error']) {
                    $errors[] = $upload['error'];
                } else {
                    $bookingId = $bookingModel->createBooking([
                        'user_id'        => $booking['user_id'],
                        'car_id'         => $booking['car_id'],
                        'start_date'     => $booking['start_date'],
                        'end_date'       => $booking['end_date'],
                        'total_days'     => $booking['total_days'],
                        'total_price'    => $booking['total_price'],
                        'notes'          => $booking['notes'],
                        'payment_method' => $booking['payment_method'],
                    ]);

                    if ($bookingId) {
                        if (!empty($booking['selfie_filename'])) {
                            $bookingModel->saveIdentityPhoto($bookingId, $booking['selfie_filename']);
                        }
                        $bookingModel->uploadPaymentProof($bookingId, $upload['filename']);

                        unset($_SESSION['pending_booking']);
                        header('Location: index.php?page=booking-success&id=' . $bookingId);
                        exit;
                    } else {
                        $errors[] = 'Failed to save booking. Please try again.';
                    }
                }
            }
        }

        require_once 'views/user/payment_proof.php';
    }


    public function success() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $bookingId    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $bookingModel = new BookingModel();
        $booking      = $bookingModel->getBookingById($bookingId);

        if (!$booking || (int)$booking['user_id'] !== (int)$_SESSION['user_id']) {
            header('Location: index.php?page=bookings');
            exit;
        }

        require_once 'views/user/booking_success.php';
    }


    private function handleUpload($inputName, $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file         = $_FILES[$inputName];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $maxSize      = 5 * 1024 * 1024;

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Failed to upload photo.', 'filename' => null];
        }
        if ($file['size'] > $maxSize) {
            return ['error' => 'The photo size must be less than 5 MB.', 'filename' => null];
        }
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowedTypes)) {
            return ['error' => 'Photo format must be JPG, PNG, or WebP.', 'filename' => null];
        }

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $prefix   = ($inputName === 'payment_proof') ? 'proof' : 'selfie';
        $filename = $prefix . '_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $dest     = $dir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return ['error' => 'Failed to save photo to server.', 'filename' => null];
        }

        return ['error' => null, 'filename' => $filename];
    }

    private function parseBookingDate($date) {
        if (!is_string($date) || $date === '') {
            return null;
        }

        $parsed = DateTime::createFromFormat('!Y-m-d', $date);
        if (!$parsed || $parsed->format('Y-m-d') !== $date) {
            return null;
        }

        return $parsed;
    }
}
?>
