<?php

require_once 'models/BookingModel.php';

class ReviewController {

    public function store() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first.']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid method.']);
            exit;
        }

        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $rating    = (int)($_POST['rating']     ?? 0);
        $comment   = trim($_POST['comment']     ?? '');

        if (!$bookingId) {
            echo json_encode(['success' => false, 'message' => 'Invalid booking data.']);
            exit;
        }
        if ($rating < 1 || $rating > 5) {
            echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5.']);
            exit;
        }
        if (mb_strlen($comment) > 500) {
            echo json_encode(['success' => false, 'message' => 'Review cannot exceed 500 characters.']);
            exit;
        }

        $bookingModel = new BookingModel();
        $booking = $bookingModel->getBookingById($bookingId);

        if (!$booking
            || (int)$booking['user_id'] !== (int)$_SESSION['user_id']
            || $booking['status'] !== 'completed') {
            echo json_encode(['success' => false, 'message' => 'Reviews can only be submitted for your completed bookings.']);
            exit;
        }

        if ($bookingModel->hasReview($bookingId)) {
            echo json_encode(['success' => false, 'message' => 'This booking has already been reviewed.']);
            exit;
        }

        $result = $bookingModel->createReview([
            'user_id'    => (int)$_SESSION['user_id'],
            'car_id'     => $booking['car_id'],
            'booking_id' => $bookingId,
            'rating'     => $rating,
            'comment'    => $comment,
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Review submitted successfully. Thank you!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save review. Ensure the booking status is Completed.']);
        }
        exit;
    }
}
?>
