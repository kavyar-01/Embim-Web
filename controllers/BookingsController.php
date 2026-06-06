<?php

require_once 'models/BookingModel.php';

class BookingsController {

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId       = $_SESSION['user_id'];
        $filterStatus = isset($_GET['status']) ? $_GET['status'] : 'all';
        $bookingModel = new BookingModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'submit_review') {
                $this->handleReview($userId, $bookingModel);
            } elseif ($_POST['action'] === 'cancel_booking') {
                $this->handleCancelBooking($userId, $bookingModel);
            }
        }

        $bookings = $bookingModel->getByUserId($userId, $filterStatus);
        $counts   = $bookingModel->getCountsByUserId($userId);

        require_once 'views/user/my_bookings.php';
    }

    private function handleCancelBooking($userId, $bookingModel) {
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        
        if (!$bookingId) {
            $_SESSION['booking_error'] = 'Invalid Booking ID.';
            header('Location: index.php?page=bookings');
            exit;
        }

        $booking = $bookingModel->getBookingById($bookingId);
        if (!$booking || (int)$booking['user_id'] !== $userId || $booking['status'] !== 'confirmed') {
            $_SESSION['booking_error'] = 'Booking tidak ditemukan atau tidak dapat dibatalkan.';
            header('Location: index.php?page=bookings');
            exit;
        }

        if ($bookingModel->updateBookingStatus($bookingId, 'cancelled')) {
            $_SESSION['review_success'] = 'Booking cancelled successfully. 80% refund will be processed shortly.';
        } else {
            $_SESSION['booking_error'] = 'Failed to cancel booking.';
        }

        header('Location: index.php?page=bookings');
        exit;
    }

    private function handleReview($userId, $bookingModel) {
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $rating    = (int)($_POST['rating']     ?? 0);
        $comment   = trim($_POST['comment']     ?? '');

        if (!$bookingId || $rating < 1 || $rating > 5) {
            $_SESSION['review_error'] = 'Invalid review data.';
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }

        $booking = $bookingModel->getBookingById($bookingId);
        if (!$booking
            || (int)$booking['user_id'] !== $userId
            || $booking['status'] !== 'completed') {
            $_SESSION['review_error'] = 'Ulasan hanya bisa diberikan untuk booking yang sudah selesai.';
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }

        if ($bookingModel->hasReview($bookingId)) {
            $_SESSION['review_error'] = 'Anda sudah memberikan ulasan untuk booking ini.';
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }

        $bookingModel->createReview([
            'user_id'    => $userId,
            'car_id'     => $booking['car_id'],
            'booking_id' => $bookingId,
            'rating'     => $rating,
            'comment'    => $comment,
        ]);

        $_SESSION['review_success'] = 'Review submitted successfully. Thank you!';
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    
}
?>