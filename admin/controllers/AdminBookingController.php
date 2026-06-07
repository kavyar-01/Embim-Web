<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/DashboardModel.php';

class AdminBookingController
{
    private BookingModel  $model;
    private DashboardModel $dashModel;
    private int $perPage = 10;

    public function __construct()
    {
        $this->model     = new BookingModel();
        $this->dashModel = new DashboardModel();
    }

    // ── LIST ─────────────────────────────────────────────────────────────────

    public function manageBookings(): void
    {
        $status = trim($_GET['status'] ?? '');
        $search = trim($_GET['search'] ?? '');

        $validStatuses = ['', 'pending', 'confirmed', 'ongoing', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses, true)) {
            $status = '';
        }

        $all         = $this->dashModel->getBookingsFiltered($status, $search);
        
        $globalAll   = $this->dashModel->getBookings();
        $stats = [
            'pending'   => 0,
            'confirmed' => 0,
            'ongoing'   => 0,
            'completed' => 0,
            'cancelled' => 0,
        ];
        foreach ($globalAll as $b) {
            $s = $b['status'] ?? 'pending';
            if (isset($stats[$s])) {
                $stats[$s]++;
            }
        }

        $total       = count($all);
        $totalPages  = max(1, (int) ceil($total / $this->perPage));
        $currentPage = max(1, min($totalPages, (int) ($_GET['p'] ?? 1)));
        $offset      = ($currentPage - 1) * $this->perPage;
        $bookings    = array_slice($all, $offset, $this->perPage);

        $topCar  = $this->dashModel->getTopCar();
        $topUser = $this->dashModel->getTopUser();

        $page = 'manage_bookings';
        require_once __DIR__ . '/../views/manage_bookings.php';
    }

    // ── DETAIL ───────────────────────────────────────────────────────────────

    public function bookingDetail(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_bookings');
            exit;
        }

        $booking = $this->model->getBookingById($id);
        if ($booking === null) {
            header('Location: ?page=manage_bookings&error=not_found');
            exit;
        }

        $page = 'manage_bookings';
        require_once __DIR__ . '/../views/booking_detail.php';
    }

    // ── EDIT (form + proses POST) ─────────────────────────────────────────────

    public function editBooking(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_bookings');
            exit;
        }

        $booking = $this->model->getBookingById($id);
        if ($booking === null) {
            header('Location: ?page=manage_bookings&error=not_found');
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $page = 'manage_bookings';
            require_once __DIR__ . '/../views/booking_edit_form.php';
            return;
        }

        // Proses POST
        $status = trim($_POST['status'] ?? '');
        $notes  = trim($_POST['notes']  ?? '');

        $allowed = ['pending', 'confirmed', 'ongoing', 'completed', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            $errors[] = 'Invalid status.';
        }

        if (!empty($errors)) {
            $page = 'manage_bookings';
            require_once __DIR__ . '/../views/booking_edit_form.php';
            return;
        }

        $ok = $this->model->updateBooking($id, $status, $notes);
        if ($ok) {
            header('Location: ?page=booking_detail&id=' . $id . '&updated=1');
        } else {
            $errors[] = 'Failed to update data. Please try again.';
            $page     = 'manage_bookings';
            require_once __DIR__ . '/../views/booking_edit_form.php';
        }
        exit;
    }

    // ── DELETE ────────────────────────────────────────────────────────────────

    public function deleteBooking(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=manage_bookings');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?page=manage_bookings&error=invalid');
            exit;
        }

        $ok = $this->model->deleteBooking($id);
        if ($ok) {
            header('Location: ?page=manage_bookings&deleted=1');
        } else {
            header('Location: ?page=manage_bookings&error=delete_failed');
        }
        exit;
    }

    /**
     * AJAX Endpoint to check for new bookings.
     */
    public function checkNewBookings(): void
    {
        header('Content-Type: application/json');
        $lastId = (int)($_GET['last_id'] ?? 0);
        
        if ($lastId === 0) {
            $all = $this->model->getNewBookingsSince(0);
            $maxId = count($all) > 0 ? max(array_column($all, 'id')) : 0;
            echo json_encode([
                'new_count' => 0,
                'max_id' => $maxId,
                'bookings' => []
            ]);
            exit;
        }

        $newBookings = $this->model->getNewBookingsSince($lastId);
        $maxId = $lastId;
        $count = count($newBookings);
        if ($count > 0) {
            $maxId = max(array_column($newBookings, 'id'));
        }
        
        echo json_encode([
            'new_count' => $count,
            'max_id' => $maxId,
            'bookings' => $newBookings
        ]);
        exit;
    }

    /**
     * AJAX Endpoint to check for cancelled bookings.
     */
    public function checkCancelledBookings(): void
    {
        header('Content-Type: application/json');
        $lastCheckedTime = $_GET['last_time'] ?? date('Y-m-d H:i:s');
        
        $cancelledBookings = $this->model->getCancelledBookingsSince($lastCheckedTime);
        $count = count($cancelledBookings);
        $newLastTime = $lastCheckedTime;
        if ($count > 0) {
            $newLastTime = max(array_column($cancelledBookings, 'updated_at'));
        }
        
        echo json_encode([
            'cancel_count' => $count,
            'last_time' => $newLastTime,
            'bookings' => $cancelledBookings
        ]);
        exit;
    }
}
