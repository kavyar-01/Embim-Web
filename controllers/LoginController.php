<?php

require_once 'models/UserModel.php';

class LoginController {

    public function index() {
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }

        $error   = '';
        $success = '';

        if (isset($_SESSION['flash_success'])) {
            $success = $_SESSION['flash_success'];
            unset($_SESSION['flash_success']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email    = trim($_POST['email']    ?? '');
            $password =      $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Email dan password wajib diisi.';

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email tidak valid.';

            } else {
                $userModel = new UserModel();
                $user      = $userModel->login($email, $password);

                if ($user) {
                    $_SESSION['user_id']    = $user['id'];
                    $_SESSION['user_name']  = $user['full_name'];
                    $_SESSION['user_role']  = $user['role'];
                    $_SESSION['user_photo'] = $user['photo_profile'] ?? null;

                    if ($user['role'] === 'admin') {
                        header('Location: index.php?page=admin-dashboard');
                    } else {
                        header('Location: index.php');
                    }
                    exit;

                } else {
                    $error = 'Email atau password yang Anda masukkan salah.';
                }
            }
        }

        require_once 'views/user/login.php';
    }

    public function loginByPhone() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method tidak valid.']);
            exit;
        }

        $phone = trim($_POST['phone'] ?? '');

        if (empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'Nomor telepon wajib diisi.']);
            exit;
        }
        if (!preg_match('/^[0-9]+$/', $phone)) {
            echo json_encode(['success' => false, 'message' => 'Nomor telepon hanya boleh berisi angka.']);
            exit;
        }
        if (strlen($phone) < 7 || strlen($phone) > 13) {
            echo json_encode(['success' => false, 'message' => 'Nomor telepon tidak valid (7–13 digit).']);
            exit;
        }

        $userModel = new UserModel();
        $user      = $userModel->findByPhone($phone);

        if ($user) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['full_name'];
            $_SESSION['user_role']  = $user['role'];
            $_SESSION['user_photo'] = $user['photo_profile'] ?? null;

            echo json_encode([
                'success'  => true,
                'message'  => 'Login berhasil!',
                'redirect' => 'index.php',
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nomor telepon tidak terdaftar di akun manapun.']);
        }
        exit;
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
?>