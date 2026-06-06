<?php

require_once 'models/UserModel.php';

class EditProfileController {

    private $uploadDir = 'assets/images/user/';

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userModel = new UserModel();
        $user      = $userModel->findById($_SESSION['user_id']);
        $errors    = [];
        $success   = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $fullName = trim($_POST['full_name'] ?? '');
            $email    = trim($_POST['email']     ?? '');
            $address  = trim($_POST['address']   ?? '');
            $password = $_POST['password']       ?? '';
            $passConf = $_POST['password_confirm'] ?? '';

            $phoneRaw = trim($_POST['phone'] ?? '');
            $phoneRaw = preg_replace('/[^0-9]/', '', $phoneRaw); 
            $phone    = !empty($phoneRaw) ? '+62' . $phoneRaw : '';

            if (empty($fullName)) {
                $errors[] = 'Full name cannot be empty.';
            } elseif (strlen($fullName) < 4) {
                $errors[] = 'Nama lengkap minimal 4 karakter.';
            }
            if (empty($email))    $errors[] = 'Email cannot be empty.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';

            if (!empty($phoneRaw) && (strlen($phoneRaw) < 7 || strlen($phoneRaw) > 13)) {
                $errors[] = 'Invalid phone number (7-13 digits).';
            }

            if (!empty($email)) {
                $existing = $userModel->findByEmail($email);
                if ($existing && (int)$existing['id'] !== (int)$_SESSION['user_id']) {
                    $errors[] = 'Email sudah digunakan akun lain.';
                }
            }

            if (!empty($password)) {
                if (strlen($password) < 8) {
                    $errors[] = 'Password minimal 8 karakter.';
                } elseif (!preg_match('/[a-z]/', $password)) {
                    $errors[] = 'Password must contain at least one lowercase letter.';
                } elseif (!preg_match('/[A-Z]/', $password)) {
                    $errors[] = 'Password must contain at least one uppercase letter.';
                } elseif (!preg_match('/[0-9]/', $password)) {
                    $errors[] = 'Password must contain at least one number.';
                } elseif ($password !== $passConf) {
                    $errors[] = 'Konfirmasi password tidak cocok.';
                }
            }

            if (!is_dir($this->uploadDir)) {
                mkdir($this->uploadDir, 0755, true);
            }

            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            $maxSize      = 2 * 1024 * 1024; // 2 MB

            $photoProfile = null;
            $photoKtp     = null;
            $photoSim     = null;

            if (!empty($_FILES['photo_profile']['name'])) {
                $result = $this->handleUpload('photo_profile', 'profile_' . $_SESSION['user_id'], $allowedTypes, $maxSize);
                if ($result['error']) {
                    $errors[] = 'Foto profil: ' . $result['error'];
                } else {
                    $photoProfile = $result['filename'];
                }
            }

            if (!empty($_FILES['photo_ktp']['name'])) {
                $result = $this->handleUpload('photo_ktp', 'ktp_' . $_SESSION['user_id'], $allowedTypes, $maxSize);
                if ($result['error']) {
                    $errors[] = 'Foto KTP: ' . $result['error'];
                } else {
                    $photoKtp = $result['filename'];
                }
            }

            if (!empty($_FILES['photo_sim']['name'])) {
                $result = $this->handleUpload('photo_sim', 'sim_' . $_SESSION['user_id'], $allowedTypes, $maxSize);
                if ($result['error']) {
                    $errors[] = 'Foto SIM: ' . $result['error'];
                } else {
                    $photoSim = $result['filename'];
                }
            }

            if (empty($errors)) {
                $data = [
                    'full_name'     => $fullName,
                    'email'         => $email,
                    'phone'         => $phone,
                    'address'       => $address,
                    'password'      => $password,
                    'photo_profile' => $photoProfile,
                    'photo_ktp'     => $photoKtp,
                    'photo_sim'     => $photoSim,
                ];

                if ($userModel->updateProfile($_SESSION['user_id'], $data)) {
                    $_SESSION['user_name']  = $fullName;
                    if ($photoProfile) {
                        $_SESSION['user_photo'] = $photoProfile;
                    }
                    $success = true;
                    $user = $userModel->findById($_SESSION['user_id']);
                } else {
                    $errors[] = 'Failed to save changes. Please try again.';
                }
            }
        }

        require_once 'views/user/edit_profile.php';
    }

    private function handleUpload($inputName, $prefix, $allowedTypes, $maxSize) {
        $file = $_FILES[$inputName];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Failed to upload file.', 'filename' => null];
        }
        if ($file['size'] > $maxSize) {
            return ['error' => 'Ukuran file maksimal 2 MB.', 'filename' => null];
        }
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowedTypes)) {
            return ['error' => 'File format must be JPG, PNG, or WebP.', 'filename' => null];
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . time() . '.' . strtolower($ext);
        $dest     = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return ['error' => 'Failed to save file to server.', 'filename' => null];
        }

        return ['error' => null, 'filename' => $filename];
    }
}
?>