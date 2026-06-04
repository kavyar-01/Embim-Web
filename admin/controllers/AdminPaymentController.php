<?php
require_once __DIR__ . '/../models/PaymentModel.php';

class AdminPaymentController {
    private PaymentModel $model;
    private int $perPage = 10;

    public function __construct() {
        $this->model = new PaymentModel();
    }

    public function managePayments(): void {
        /* ── Filter: Status & Search ───────────────────────────── */
        $status = trim($_GET['status'] ?? '');
        $search = trim($_GET['search'] ?? '');

        $validStatuses = ['', 'unpaid', 'paid', 'refunded'];
        if (!in_array($status, $validStatuses, true)) {
            $status = '';
        }

        /* ── Filter: Rentang Harga ─────────────────────────────── */
        $amountMin = trim($_GET['amount_min'] ?? '');
        $amountMax = trim($_GET['amount_max'] ?? '');

        // Sanitasi: hanya angka
        if (!is_numeric($amountMin)) $amountMin = '';
        if (!is_numeric($amountMax)) $amountMax = '';

        /* ── Filter: Rentang Waktu ─────────────────────────────── */
        $timeMode = trim($_GET['time_mode'] ?? '');
        $validModes = ['', 'range', 'month', 'year'];
        if (!in_array($timeMode, $validModes, true)) {
            $timeMode = '';
        }

        $dateFrom = '';
        $dateTo   = '';
        $month    = '';
        $year     = '';

        if ($timeMode === 'range') {
            $dateFrom = trim($_GET['date_from'] ?? '');
            $dateTo   = trim($_GET['date_to']   ?? '');
            // Validasi format YYYY-MM-DD
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) $dateFrom = '';
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo))   $dateTo   = '';
        } elseif ($timeMode === 'month') {
            $month = trim($_GET['month'] ?? '');
            if (!preg_match('/^\d{4}-\d{2}$/', $month)) $month = '';
        } elseif ($timeMode === 'year') {
            $year = trim($_GET['year'] ?? '');
            if (!preg_match('/^\d{4}$/', $year)) $year = '';
        }

        /* ── Query & Paginasi ──────────────────────────────────── */
        $all = $this->model->getAllPayments(
            $status, $search,
            $amountMin, $amountMax,
            $timeMode, $dateFrom, $dateTo, $month, $year
        );

        $total       = count($all);
        $totalPages  = max(1, (int) ceil($total / $this->perPage));
        $currentPage = max(1, min($totalPages, (int) ($_GET['p'] ?? 1)));
        $offset      = ($currentPage - 1) * $this->perPage;
        $payments    = array_slice($all, $offset, $this->perPage);

        /* ── Kirim semua filter ke view (untuk mengisi ulang form) */
        $filterVars = compact(
            'status', 'search',
            'amountMin', 'amountMax',
            'timeMode', 'dateFrom', 'dateTo', 'month', 'year'
        );

        $page = 'manage_payments';
        require_once __DIR__ . '/../views/manage_payments.php';
    }

    public function paymentDetail(): void {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_payments');
            exit;
        }

        $payment = $this->model->getPaymentById($id);
        if ($payment === null) {
            header('Location: ?page=manage_payments');
            exit;
        }

        $page = 'manage_payments';
        require_once __DIR__ . '/../views/payment_detail.php';
    }

    public function updateStatus(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=manage_payments');
            exit;
        }

        $id     = (int) ($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        $allowed = ['unpaid', 'paid', 'refunded'];
        if ($id <= 0 || !in_array($status, $allowed, true)) {
            header('Location: ?page=manage_payments&error=invalid');
            exit;
        }

        $ok = $this->model->updateStatus($id, $status);
        if ($ok) {
            header('Location: ?page=payment_detail&id=' . $id . '&updated=1');
        } else {
            header('Location: ?page=payment_detail&id=' . $id . '&error=1');
        }
        exit;
    }
}