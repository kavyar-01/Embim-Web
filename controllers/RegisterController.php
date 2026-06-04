<?php

require_once 'models/UserModel.php';

class RegisterController {

    public function index() {
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $fullName = trim(htmlspecialchars($_POST['full_name'] ?? ''));
            $email    = trim(htmlspecialchars($_POST['email']     ?? ''));
            $password = $_POST['password'] ?? '';
            $agree    = isset($_POST['agree_terms']);

            $phoneRaw = trim($_POST['phone'] ?? '');
            $phoneRaw = preg_replace('/[^0-9]/', '', $phoneRaw); 
            $phone    = !empty($phoneRaw) ? '+62' . $phoneRaw : '';

            if (empty($fullName) || empty($email) || empty($password)) {
                $error = 'Nama, email, dan password wajib diisi.';

            } elseif (strlen($fullName) < 4) {
                $error = 'Nama lengkap minimal 4 karakter.';

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email tidak valid.';

            } elseif (!empty($phoneRaw) && !preg_match('/^[0-9]+$/', $phoneRaw)) {
                $error = 'Nomor telepon hanya boleh berisi angka.';

            } elseif (!empty($phoneRaw) && (strlen($phoneRaw) < 7 || strlen($phoneRaw) > 13)) {
                $error = 'Nomor telepon tidak valid (7–13 digit).';

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email tidak valid.';

            } elseif (strlen($password) < 8) {
                $error = 'Password minimal 8 karakter.';

            } elseif (!preg_match('/[a-z]/', $password)) {
                $error = 'Password harus mengandung minimal satu huruf kecil.';

            } elseif (!preg_match('/[A-Z]/', $password)) {
                $error = 'Password harus mengandung minimal satu huruf besar.';

            } elseif (!preg_match('/[0-9]/', $password)) {
                $error = 'Password harus mengandung minimal satu angka.';

            } elseif (!$agree) {
                $error = 'Anda harus menyetujui Syarat & Ketentuan untuk melanjutkan.';

            } else {
                $userModel = new UserModel();
                $result    = $userModel->register($fullName, $email, $phone, $password);

                if ($result['success']) {
                    $_SESSION['flash_success'] = 'Akun berhasil dibuat! Silakan login.';
                    header('Location: index.php?page=login');
                    exit;
                } else {
                    $error = $result['message'];
                }
            }
        }

        require_once 'views/user/register.php';
    }
}
?>