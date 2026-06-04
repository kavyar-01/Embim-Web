<?php
  require_once __DIR__ . '/../config/database.php';

  class AuthModel {

      /** Find a user by email where role = 'admin'. Returns row or false. */
      public function findAdminByEmail(string $email): array|false {
          $stmt = getPDO()->prepare(
              "SELECT * FROM `users` WHERE `email` = :email AND `role` = 'admin' LIMIT 1"
          );
          $stmt->execute([':email' => $email]);
          return $stmt->fetch();
      }

      /** Register a new admin (role hardcoded to 'admin'). Returns true or 'duplicate'. */
      public function createAdmin(array $data): bool|string {
          try {
              $hash = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
              $stmt = getPDO()->prepare("
                  INSERT INTO `users` (`full_name`, `email`, `password`, `phone`, `role`)
                  VALUES (:full_name, :email, :password, :phone, 'admin')
              ");
              $stmt->execute([
                  ':full_name' => $data['full_name'],
                  ':email'     => $data['email'],
                  ':password'  => $hash,
                  ':phone'     => $data['phone'] ?: null,
              ]);
              return true;
          } catch (\PDOException $e) {
              if ($e->getCode() === '23000') return 'duplicate';
              throw $e;
          }
      }
  }
  