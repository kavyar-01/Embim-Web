<?php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    public function getUsers(): array {
        return getPDO()->query("SELECT `id`, `full_name`, `email`, `phone`, `role`, `created_at`, `updated_at` FROM `users` ORDER BY `id` ASC")->fetchAll();
    }
    public function getStats(array $users): array {
        return [
            'total'    => count($users),
            'active'   => count($users),
            'inactive' => 0,
            'admins'   => count(array_filter($users, fn($u) => $u['role'] === 'admin')),
        ];
    }
}
