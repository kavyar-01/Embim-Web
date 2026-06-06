<?php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    public function getUsers(): array {
        return getPDO()->query("SELECT `id`, `full_name`, `email`, `phone`, `role`, `photo_profile`, `created_at`, `updated_at` FROM `users` ORDER BY `id` ASC")->fetchAll();
    }
    public function getStats(array $users): array {
        return [
            'total'    => count($users),
            'active'   => count($users),
            'users'    => count(array_filter($users, fn($u) => $u['role'] === 'user')),
            'admins'   => count(array_filter($users, fn($u) => $u['role'] === 'admin')),
        ];
    }
    public function deleteUser(int $id): bool {
        $stmt = getPDO()->prepare("DELETE FROM `users` WHERE `id` = :id AND `role` != 'admin'");
        return $stmt->execute([':id' => $id]);
    }
}
