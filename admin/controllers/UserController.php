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
        if ($search !== '') {
            $all = array_values(array_filter($all, fn($u) => stripos($u['full_name'],$search)!==false || stripos($u['email'],$search)!==false || stripos($u['role'],$search)!==false));
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
            $message = match($_POST['action']) { 'delete' => 'User removed successfully.', 'toggle' => 'User status updated successfully.', default => '' };
        }
        $page = 'manage_users';
        require_once __DIR__ . '/../views/manage_users.php';
    }
}
