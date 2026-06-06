<?php

require_once 'config/database.php';

class UserModel {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $db         = new Database();
        $this->conn = $db->getConnection();
    }


    public function register($fullName, $email, $phone, $password) {
        if ($this->findByEmail($email)) {
            return ['success' => false, 'message' => 'Email is already registered. Silakan gunakan email lain atau login.'];
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $sql  = "INSERT INTO {$this->table} (full_name, email, phone, password, role)
                 VALUES (:full_name, :email, :phone, :password, 'user')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':email',     $email);
        $stmt->bindParam(':phone',     $phone);
        $stmt->bindParam(':password',  $hashedPassword);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Account successfully created! Please login.'];
        }
        return ['success' => false, 'message' => 'An error occurred. Please try again.'];
    }


    public function login($email, $password) {
        $user = $this->findByEmail($email);
        if (!$user) {
            return false;
        }
        if (password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function findByEmail($email) {
        $sql  = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }


    public function findById($id) {
        $sql  = "SELECT id, full_name, email, phone, address, role,
                        photo_profile, photo_ktp, photo_sim,
                        created_at, updated_at
                 FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }


    public function updateProfile($id, $data) {
        $fields = [
            'full_name = :full_name',
            'email     = :email',
            'phone     = :phone',
            'address   = :address',
        ];
        $params = [
            ':full_name' => $data['full_name'],
            ':email'     => $data['email'],
            ':phone'     => $data['phone'],
            ':address'   => $data['address'],
            ':id'        => $id,
        ];

        if (!empty($data['password'])) {
            $fields[]             = 'password = :password';
            $params[':password']  = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        if (!empty($data['photo_profile'])) {
            $fields[]                 = 'photo_profile = :photo_profile';
            $params[':photo_profile'] = $data['photo_profile'];
        }

        if (!empty($data['photo_ktp'])) {
            $fields[]             = 'photo_ktp = :photo_ktp';
            $params[':photo_ktp'] = $data['photo_ktp'];
        }

        if (!empty($data['photo_sim'])) {
            $fields[]             = 'photo_sim = :photo_sim';
            $params[':photo_sim'] = $data['photo_sim'];
        }

        $sql  = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }


    public function findByPhone($phone) {
        $phoneNorm = '+62' . preg_replace('/[^0-9]/', '', $phone);
        $sql  = "SELECT * FROM {$this->table} WHERE phone = :phone AND role = 'user' LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':phone' => $phoneNorm]);
        return $stmt->fetch();
    }

    public function verifyIdentity($fullName, $phone) {
        $phoneNorm = '+62' . preg_replace('/[^0-9]/', '', $phone);
        $sql  = "SELECT id, full_name, phone FROM {$this->table}
                 WHERE LOWER(full_name) = LOWER(:full_name)
                   AND phone = :phone
                   AND role  = 'user'
                 LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':full_name' => trim($fullName), ':phone' => $phoneNorm]);
        return $stmt->fetch();
    }


    public function updatePassword($userId, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        $sql    = "UPDATE {$this->table} SET password = :password WHERE id = :id";
        $stmt   = $this->conn->prepare($sql);
        return $stmt->execute([':password' => $hashed, ':id' => $userId]);
    }
}
?>