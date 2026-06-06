<?php

require_once 'models/CarModel.php';
require_once 'models/BookingModel.php';

class BookingController {

    private $uploadDir        = 'assets/images/booking/';
    private $proofUploadDir   = 'assets/images/payment_proof/';


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

        // Cek apakah user masih punya booking aktif (belum completed/cancelled)
        if ($bookingModel->hasActiveBooking($_SESSION['user_id'])) {
            $_SESSION['booking_error'] = 'Anda masih memiliki booking aktif. Selesaikan terlebih dahulu sebelum melakukan booking baru.';
            header('Location: index.php?page=bookings');
            exit;
        }

        // Cek apakah user memiliki denda yang belum dibayar
        if ($bookingModel->hasUnpaidFines($_SESSION['user_id'])) {
            $_SESSION['booking_error'] = 'Anda memiliki denda yang belum dibayar. Harap selesaikan pembayaran denda terlebih dahulu sebelum melakukan booking baru.';
            header('Location: index.php?page=bookings');
            exit;
        }

        $totalDays  = 0;
        $totalPrice = 0;
        if ($startDate && $endDate) {
            $start = new DateTime($startDate);
            $end   = new DateTime($endDate);
            $diff  = $start->diff($end)->days;
            if ($diff > 0) {
                $totalDays  = $diff;
                $totalPrice = $diff * (int)$car['price_per_day'];
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $notes  = trim($_POST['notes']          ?? '');
            $method = trim($_POST['payment_method'] ?? '');

            if (!$carId)           $errors[] = 'Data kendaraan tidak valid.';
            if (empty($startDate)) $errors[] = 'Tanggal pickup wajib diisi.';
            if (empty($endDate))   $errors[] = 'Tanggal kembali wajib diisi.';
            if (empty($method))    $errors[] = 'Metode pembayaran wajib dipilih.';

            if ($startDate && $endDate) {
                $start = new DateTime($startDate);
                $end   = new DateTime($endDate);
                $today = new DateTime('today');

                if ($start < $today)  $errors[] = 'Tanggal pickup tidak boleh di masa lalu.';
                if ($end <= $start)   $errors[] = 'Tanggal kembali harus setelah tanggal pickup.';

                $totalDays  = $start->diff($end)->days;
                $totalPrice = $totalDays * (int)$car['price_per_day'];
            }

            $selfieFilename = null;
            if (empty($_FILES['selfie_ktp']['name'])) {
                $errors[] = 'Foto selfie memegang KTP wajib diunggah.';
            } else {
                $upload = $this->handleUpload('selfie_ktp', $this->uploadDir);
                if ($upload['error']) {
                    $errors[] = $upload['error'];
                } else {
                    $selfieFilename = $upload['filename'];
                }
            }

            if (empty($errors)) {
                if (!$bookingModel->isCarAvailable($carId, $startDate, $endDate)) {
                    $errors[] = 'Kendaraan tidak tersedia pada tanggal yang dipilih.';
                }
            }

            if (empty($errors)) {
                if (!$bookingModel->isCarAvailable($carId, $startDate, $endDate)) {
                    $errors[] = 'Kendaraan tidak tersedia pada tanggal yang dipilih.';
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
            if (empty($_FILES['payment_proof']['name'])) {
                $errors[] = 'Bukti pembayaran wajib diunggah.';
            } else {
                $upload = $this->handleUpload('payment_proof', $this->proofUploadDir);
                if ($upload['error']) {
                    $errors[] = $upload['error'];
                } else {
                    $bookingModel = new BookingModel();

                    // Check availability one last time
                    if (!$bookingModel->isCarAvailable($booking['car_id'], $booking['start_date'], $booking['end_date'])) {
                        $errors[] = 'Maaf, kendaraan sudah tidak tersedia pada tanggal tersebut.';
                    } else {
                        // Create Booking
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
                            $errors[] = 'Gagal menyimpan booking. Silakan coba lagi.';
                        }
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
        $maxSize      = 5 * 1024 * 1024; // 5 MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Gagal mengupload foto.', 'filename' => null];
        }
        if ($file['size'] > $maxSize) {
            return ['error' => 'Ukuran foto maksimal 5 MB.', 'filename' => null];
        }
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowedTypes)) {
            return ['error' => 'Format foto harus JPG, PNG, atau WebP.', 'filename' => null];
        }

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $prefix   = ($inputName === 'payment_proof') ? 'proof' : 'selfie';
        $filename = $prefix . '_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $dest     = $dir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return ['error' => 'Gagal menyimpan foto ke server.', 'filename' => null];
        }

        return ['error' => null, 'filename' => $filename];
    }
}
?>