<?php
require_once __DIR__ . '/../models/ReturnModel.php';

class AdminReturnController {
    private ReturnModel $model;
    private int $perPage = 10;

    public function __construct() {
        $this->model = new ReturnModel();
    }

    // ── LIST ─────────────────────────────────────────────────────────────────

    public function manageReturns(): void {
        $condition  = trim($_GET['condition']    ?? '');
        $search     = trim($_GET['search']       ?? '');
        $returnDate = trim($_GET['return_date']  ?? '');

        $validConditions = ['', 'good', 'damaged'];
        if (!in_array($condition, $validConditions, true)) {
            $condition = '';
        }

        $all        = $this->model->getAllReturns($condition, $search, $returnDate);
        $total      = count($all);
        $totalPages = max(1, (int) ceil($total / $this->perPage));
        $currentPage = max(1, min($totalPages, (int) ($_GET['p'] ?? 1)));
        $offset     = ($currentPage - 1) * $this->perPage;
        $returns    = array_slice($all, $offset, $this->perPage);

        $page = 'manage_returns';
        require_once __DIR__ . '/../views/manage_returns.php';
    }

    // ── DETAIL ───────────────────────────────────────────────────────────────

    public function returnDetail(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_returns');
            exit;
        }

        $return = $this->model->getReturnById($id);
        if ($return === null) {
            header('Location: ?page=manage_returns&error=not_found');
            exit;
        }

        $page = 'manage_returns';
        require_once __DIR__ . '/../views/return_detail.php';
    }

    // ── ADD (form + proses POST) ──────────────────────────────────────────────

    public function addReturn(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Tampilkan form
            $bookings = $this->model->getBookingsWithoutReturn();
            $page     = 'manage_returns';
            require_once __DIR__ . '/../views/return_form.php';
            return;
        }

        // Proses POST
        $bookingId    = (int)   trim($_POST['booking_id']    ?? '0');
        $returnDate   =         trim($_POST['return_date']   ?? '');
        $carCondition =         trim($_POST['car_condition'] ?? '');
        $notes        =         trim($_POST['notes']         ?? '');

        // Validasi
        $errors = $this->validate($bookingId, $returnDate, $carCondition, 0);

        if (!empty($errors)) {
            $bookings = $this->model->getBookingsWithoutReturn();
            $page     = 'manage_returns';
            require_once __DIR__ . '/../views/return_form.php';
            return;
        }

        $newId = $this->model->createReturn($bookingId, $returnDate, $carCondition, $notes);
        if ($newId !== false) {
            header('Location: ?page=return_detail&id=' . $newId . '&created=1');
        } else {
            $errors[]  = 'Gagal menyimpan data. Silakan coba lagi.';
            $bookings  = $this->model->getBookingsWithoutReturn();
            $page      = 'manage_returns';
            require_once __DIR__ . '/../views/return_form.php';
        }
        exit;
    }

    // ── EDIT (form + proses POST) ─────────────────────────────────────────────

    public function editReturn(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_returns');
            exit;
        }

        $return = $this->model->getReturnById($id);
        if ($return === null) {
            header('Location: ?page=manage_returns&error=not_found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Tampilkan form edit
            $page = 'manage_returns';
            require_once __DIR__ . '/../views/return_edit_form.php';
            return;
        }

        // Proses POST
        $returnDate   = trim($_POST['return_date']   ?? '');
        $carCondition = trim($_POST['car_condition'] ?? '');
        $notes        = trim($_POST['notes']         ?? '');

        $errors = $this->validate($return['booking_id'], $returnDate, $carCondition, $id);

        if (!empty($errors)) {
            $page = 'manage_returns';
            require_once __DIR__ . '/../views/return_edit_form.php';
            return;
        }

        $ok = $this->model->updateReturn($id, $returnDate, $carCondition, $notes);
        if ($ok) {
            header('Location: ?page=return_detail&id=' . $id . '&updated=1');
        } else {
            $errors[] = 'Gagal memperbarui data. Silakan coba lagi.';
            $page     = 'manage_returns';
            require_once __DIR__ . '/../views/return_edit_form.php';
        }
        exit;
    }

    // ── DELETE ────────────────────────────────────────────────────────────────

    public function deleteReturn(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=manage_returns');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_returns&error=invalid');
            exit;
        }

        $ok = $this->model->deleteReturn($id);
        if ($ok) {
            header('Location: ?page=manage_returns&deleted=1');
        } else {
            header('Location: ?page=manage_returns&error=delete_failed');
        }
        exit;
    }

    // ── HELPERS ───────────────────────────────────────────────────────────────

    private function validate(int $bookingId, string $returnDate, string $carCondition, int $excludeId): array {
        $errors = [];

        if ($bookingId <= 0) {
            $errors[] = 'Booking ID wajib dipilih.';
        } else {
            $booking = $this->model->getBookingById($bookingId);
            if ($booking === null) {
                $errors[] = 'Booking ID tidak ditemukan.';
            } elseif ($this->model->existsByBookingId($bookingId, $excludeId)) {
                $errors[] = 'Booking #' . $bookingId . ' sudah memiliki data pengembalian.';
            }
        }

        if ($returnDate === '') {
            $errors[] = 'Tanggal pengembalian wajib diisi.';
        } elseif (!strtotime($returnDate)) {
            $errors[] = 'Format tanggal pengembalian tidak valid.';
        }

        if (!in_array($carCondition, ['good', 'damaged'], true)) {
            $errors[] = 'Kondisi kendaraan hanya boleh "good" atau "damaged".';
        }

        return $errors;
    }
}
