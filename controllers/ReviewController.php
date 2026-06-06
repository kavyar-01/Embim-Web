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
        $carId     = (int)($_POST['car_id']     ?? 0);
        $rating    = (int)($_POST['rating']     ?? 0);
        $comment   = trim($_POST['comment']     ?? '');

        if (!$bookingId || !$carId) {
            echo json_encode(['success' => false, 'message' => 'Invalid booking data.']);
            exit;
        }
        if ($rating < 1 || $rating > 5) {
            echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5.']);
            exit;
        }
        if (empty($comment)) {
            echo json_encode(['success' => false, 'message' => 'Review cannot be empty.']);
            exit;
        }
        if (mb_strlen($comment) < 10) {
            echo json_encode(['success' => false, 'message' => 'Ulasan minimal 10 karakter.']);
            exit;
        }

        $bookingModel = new BookingModel();

        if ($bookingModel->hasReview($bookingId)) {
            echo json_encode(['success' => false, 'message' => 'Booking ini sudah pernah direview.']);
            exit;
        }

        $result = $bookingModel->createReview(
            $_SESSION['user_id'],
            $carId,
            $bookingId,
            $rating,
            $comment
        );

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Review submitted successfully. Thank you!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save review. Ensure the booking status is Completed.']);
        }
        exit;
    }
}
?>
