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

      /** Verify admin identity by full_name and phone. Returns row or false. */
      public function verifyIdentity(string $fullName, string $phone): array|false {
          $stmt = getPDO()->prepare(
              "SELECT * FROM `users` WHERE `full_name` = :full_name AND `phone` = :phone AND `role` = 'admin' LIMIT 1"
          );
          $stmt->execute([':full_name' => $fullName, ':phone' => $phone]);
          return $stmt->fetch();
      }

      /** Update admin password */
      public function updatePassword(int $userId, string $newPassword): bool {
          $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
          $stmt = getPDO()->prepare(
              "UPDATE `users` SET `password` = :password WHERE `id` = :id AND `role` = 'admin'"
          );
          return $stmt->execute([':password' => $hash, ':id' => $userId]);
      }

      /** Find admin by ID */
      public function findAdminById(int $id): array|false {
          $stmt = getPDO()->prepare(
              "SELECT * FROM `users` WHERE `id` = :id AND `role` = 'admin' LIMIT 1"
          );
          $stmt->execute([':id' => $id]);
          return $stmt->fetch();
      }

      /** Update admin profile */
      public function updateProfile(int $id, array $data): bool {
          $fields = [
              'full_name = :full_name',
              'email     = :email',
              'phone     = :phone'
          ];
          $params = [
              ':full_name' => $data['full_name'],
              ':email'     => $data['email'],
              ':phone'     => $data['phone'] ?: null,
              ':id'        => $id
          ];

          if (!empty($data['password'])) {
              $fields[] = 'password = :password';
              $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
          }

          if (!empty($data['photo_profile'])) {
              $fields[] = 'photo_profile = :photo_profile';
              $params[':photo_profile'] = $data['photo_profile'];
          }

          $sql = "UPDATE `users` SET " . implode(', ', $fields) . " WHERE `id` = :id AND `role` = 'admin'";
          $stmt = getPDO()->prepare($sql);
          return $stmt->execute($params);
      }
  }
  