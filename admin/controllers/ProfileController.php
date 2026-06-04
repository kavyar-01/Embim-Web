<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/AuthModel.php';

class ProfileController {
    private AuthModel $model;
    private string $uploadDir = __DIR__ . '/../../assets/images/user/';

    public function __construct() { 
        $this->model = new AuthModel(); 
    }

    public function editProfile(): void {
        if (empty($_SESSION['admin_id'])) {
            header('Location: ?page=login');
            exit;
        }

        $adminId = (int)$_SESSION['admin_id'];
        $admin = $this->model->findAdminById($adminId);
        $errors = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['full_name'] ?? '');
            $email    = trim($_POST['email']     ?? '');
            $phone    = trim($_POST['phone']     ?? '');
            $password = $_POST['password']       ?? '';
            $passConf = $_POST['password_confirm'] ?? '';

            if (empty($fullName)) $errors[] = 'Nama lengkap tidak boleh kosong.';
            if (empty($email))    $errors[] = 'Email tidak boleh kosong.';
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';

            if (!empty($email)) {
                $existing = $this->model->findAdminByEmail($email);
                if ($existing && (int)$existing['id'] !== $adminId) {
                    $errors[] = 'Email sudah digunakan oleh akun lain.';
                }
            }

            if (!empty($password)) {
                if (strlen($password) < 8) {
                    $errors[] = 'Password minimal 8 karakter.';
                } elseif ($password !== $passConf) {
                    $errors[] = 'Konfirmasi password tidak cocok.';
                }
            }

            if (!empty($phone) && !str_starts_with($phone, '+62')) {
                $phone = '+62' . ltrim($phone, '0');
            }

            if (!is_dir($this->uploadDir)) {
                mkdir($this->uploadDir, 0755, true);
            }

            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            $maxSize      = 2 * 1024 * 1024; // 2 MB
            $photoProfile = null;

            if (!empty($_FILES['photo_profile']['name'])) {
                $result = $this->handleUpload('photo_profile', 'admin_' . $adminId, $allowedTypes, $maxSize);
                if ($result['error']) {
                    $errors[] = 'Foto profil: ' . $result['error'];
                } else {
                    $photoProfile = $result['filename'];
                }
            }

            if (empty($errors)) {
                $data = [
                    'full_name'     => $fullName,
                    'email'         => $email,
                    'phone'         => $phone,
                    'password'      => $password,
                    'photo_profile' => $photoProfile,
                ];

                if ($this->model->updateProfile($adminId, $data)) {
                    $_SESSION['admin_name']  = $fullName;
                    $_SESSION['admin_email'] = $email;
                    if ($photoProfile) {
                        $_SESSION['admin_photo'] = $photoProfile;
                    }
                    $success = true;
                    $admin = $this->model->findAdminById($adminId);
                } else {
                    $errors[] = 'Gagal menyimpan perubahan. Silakan coba lagi.';
                }
            }
        }

        require_once __DIR__ . '/../views/profile/edit.php';
    }

    private function handleUpload(string $inputName, string $prefix, array $allowedTypes, int $maxSize): array {
        $file = $_FILES[$inputName];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Gagal mengupload file.', 'filename' => null];
        }
        if ($file['size'] > $maxSize) {
            return ['error' => 'Ukuran file maksimal 2 MB.', 'filename' => null];
        }
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowedTypes)) {
            return ['error' => 'Format file harus JPG, PNG, atau WebP.', 'filename' => null];
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . time() . '.' . strtolower($ext);
        $dest     = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return ['error' => 'Gagal menyimpan file ke server.', 'filename' => null];
        }

        return ['error' => null, 'filename' => $filename];
    }
}
