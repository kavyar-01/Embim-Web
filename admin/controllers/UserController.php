<?php
require_once __DIR__ . '/../models/UserModel.php';
class UserController {
    private UserModel $model;
    private int $perPage = 10;
    public function __construct() { $this->model = new UserModel(); }
    public function manageUsers(): void {
        $all    = $this->model->getUsers();
        $stats  = $this->model->getStats($all);
        $search = trim($_GET['search'] ?? '');
        $roleF  = trim($_GET['role'] ?? '');
        
        if ($search !== '' || $roleF !== '') {
            $all = array_values(array_filter($all, function($u) use ($search, $roleF) {
                $matchSearch = true;
                if ($search !== '') {
                    $matchSearch = stripos((string)$u['id'], $search) !== false
                                || stripos($u['full_name'], $search) !== false 
                                || stripos($u['email'], $search) !== false;
                }
                $matchRole = true;
                if ($roleF !== '' && $roleF !== 'all' && $roleF !== 'active') {
                    $matchRole = ($u['role'] === $roleF);
                }
                return $matchSearch && $matchRole;
            }));
        } else {
            $all = array_values($all);
        }
        $total      = count($all);
        $totalPages = max(1, (int)ceil($total / $this->perPage));
        $currentPage = max(1, min($totalPages, (int)($_GET['p'] ?? 1)));
        $offset     = ($currentPage - 1) * $this->perPage;
        $users      = array_slice($all, $offset, $this->perPage);
        $message    = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'delete') {
                $id = (int)($_POST['user_id'] ?? 0);
                if ($id > 0 && $this->model->deleteUser($id)) {
                    $message = 'User removed successfully.';
                    // Refresh data after delete
                    $all = $this->model->getUsers();
                    $total = count($all);
                    $totalPages = max(1, (int)ceil($total / $this->perPage));
                    $currentPage = max(1, min($totalPages, $currentPage));
                    $offset = ($currentPage - 1) * $this->perPage;
                    $users = array_slice($all, $offset, $this->perPage);
                } else {
                    $message = 'Failed to remove user. Admin cannot be deleted.';
                }
            } elseif ($_POST['action'] === 'toggle') {
                $message = 'User status updated successfully.';
            }
        }
        $page = 'manage_users';
        require_once __DIR__ . '/../views/manage_users.php';
    }
}
