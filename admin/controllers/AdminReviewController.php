<?php
require_once __DIR__ . '/../models/ReviewModel.php';

class AdminReviewController {
    private ReviewModel $model;

    public function __construct() {
        $this->model = new ReviewModel();
    }

    // ── LIST ─────────────────────────────────────────────────────────────────
    public function manageReviews(): void {
        $allReviews = $this->model->getAllReviews();
        
        $stats = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($allReviews as $r) {
            $rating = (int) $r['rating'];
            if (isset($stats[$rating])) {
                $stats[$rating]++;
            }
        }

        $total      = count($allReviews);
        $perPage    = 6;
        $totalPages = max(1, (int) ceil($total / $perPage));
        $currentPage = max(1, min($totalPages, (int) ($_GET['p'] ?? 1)));
        $offset     = ($currentPage - 1) * $perPage;
        
        $reviews = array_slice($allReviews, $offset, $perPage);

        $page = 'manage_reviews';
        require_once __DIR__ . '/../views/manage_reviews.php';
    }

    // ── EDIT (form + proses POST) ─────────────────────────────────────────────
    public function editReview(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_reviews');
            exit;
        }

        $review = $this->model->getReviewById($id);
        if ($review === null) {
            header('Location: ?page=manage_reviews&error=not_found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Tampilkan form edit
            $page = 'manage_reviews';
            require_once __DIR__ . '/../views/review_edit_form.php';
            return;
        }

        // Proses POST
        $rating  = (int) ($_POST['rating'] ?? 5);
        $comment = trim($_POST['comment'] ?? '');
        $errors  = [];

        if ($rating < 1 || $rating > 5) {
            $errors[] = 'Rating must be between 1 and 5.';
        }
        if (mb_strlen($comment) > 500) {
            $errors[] = 'Comment cannot exceed 500 characters.';
        }

        if (empty($errors)) {
            $ok = $this->model->updateReview($id, $rating, $comment);
            if ($ok) {
                header('Location: ?page=manage_reviews&updated=1');
                exit;
            } else {
                $errors[] = 'Failed to update review. Please try again.';
            }
        }

        // Jika error
        $page = 'manage_reviews';
        require_once __DIR__ . '/../views/review_edit_form.php';
    }

    // ── DELETE ────────────────────────────────────────────────────────────────
    public function deleteReview(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=manage_reviews');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_reviews&error=invalid');
            exit;
        }

        $ok = $this->model->deleteReview($id);
        if ($ok) {
            header('Location: ?page=manage_reviews&deleted=1');
        } else {
            header('Location: ?page=manage_reviews&error=delete_failed');
        }
        exit;
    }
}
