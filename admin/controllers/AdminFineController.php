<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/FineModel.php';

class AdminFineController
{
    private FineModel $model;
    private int $perPage = 10;

    public function __construct()
    {
        $this->model = new FineModel();
    }

    // ── LIST ─────────────────────────────────────────────────────────────────

    public function manageFines(): void
    {
        $status = trim($_GET['status'] ?? '');
        $search = trim($_GET['search'] ?? '');

        $validStatuses = ['', 'unpaid', 'paid'];
        if (!in_array($status, $validStatuses, true)) {
            $status = '';
        }

        $all         = $this->model->getAllFines($status, $search);
        $total       = count($all);
        $totalPages  = max(1, (int) ceil($total / $this->perPage));
        $currentPage = max(1, min($totalPages, (int) ($_GET['p'] ?? 1)));
        $offset      = ($currentPage - 1) * $this->perPage;
        $fines       = array_slice($all, $offset, $this->perPage);

        $page = 'manage_fines';
        require_once __DIR__ . '/../views/manage_fines.php';
    }

    // ── DETAIL ───────────────────────────────────────────────────────────────

    public function fineDetail(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_fines');
            exit;
        }

        $fine = $this->model->getFineById($id);
        if ($fine === null) {
            header('Location: ?page=manage_fines&error=not_found');
            exit;
        }

        $page = 'manage_fines';
        require_once __DIR__ . '/../views/fine_detail.php';
    }

    // ── UPDATE STATUS ─────────────────────────────────────────────────────────

    public function updateFineStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=manage_fines');
            exit;
        }

        $id     = (int) ($_POST['id']     ?? 0);
        $status = trim($_POST['status']   ?? '');

        $allowed = ['unpaid', 'paid'];
        if ($id <= 0 || !in_array($status, $allowed, true)) {
            header('Location: ?page=manage_fines&error=invalid');
            exit;
        }

        // Pastikan fine dengan ID ini memang ada
        $fine = $this->model->getFineById($id);
        if ($fine === null) {
            header('Location: ?page=manage_fines&error=not_found');
            exit;
        }

        $ok = $this->model->updateStatus($id, $status);
        if ($ok) {
            header('Location: ?page=fine_detail&id=' . $id . '&updated=1');
        } else {
            header('Location: ?page=fine_detail&id=' . $id . '&error=1');
        }
        exit;
    }
}
