<?php

require_once 'models/UserModel.php';

class ForgotPasswordController {

    public function verify() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid method.']);
            exit;
        }

        $fullName = trim($_POST['full_name'] ?? '');
        $phone    = trim($_POST['phone']     ?? '');

        if (empty($fullName) || empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'Full name and phone number are required.']);
            exit;
        }

        if (!preg_match('/^[0-9]+$/', $phone)) {
            echo json_encode(['success' => false, 'message' => 'Nomor telepon hanya boleh berisi angka.']);
            exit;
        }

        $userModel = new UserModel();
        $user      = $userModel->verifyIdentity($fullName, $phone);

        if ($user) {
            $_SESSION['reset_user_id'] = $user['id'];
            echo json_encode([
                'success' => true,
                'user_id' => $user['id'],
                'message' => 'Identitas terverifikasi.',
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nama lengkap atau nomor telepon tidak ditemukan.']);
        }
        exit;
    }

    public function reset() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid method.']);
            exit;
        }

        $userId   = (int)($_POST['user_id']  ?? 0);
        $password = $_POST['password']        ?? '';
        $confirm  = $_POST['confirm']         ?? '';

        if (!isset($_SESSION['reset_user_id']) || (int)$_SESSION['reset_user_id'] !== $userId) {
            echo json_encode(['success' => false, 'message' => 'Invalid session. Please repeat verification.']);
            exit;
        }

        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'Password minimal 8 karakter.']);
            exit;
        }
        if (!preg_match('/[a-z]/', $password)) {
            echo json_encode(['success' => false, 'message' => 'Password must contain at least one lowercase letter.']);
            exit;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            echo json_encode(['success' => false, 'message' => 'Password must contain at least one uppercase letter.']);
            exit;
        }
        if (!preg_match('/[0-9]/', $password)) {
            echo json_encode(['success' => false, 'message' => 'Password must contain at least one number.']);
            exit;
        }
        if ($password !== $confirm) {
            echo json_encode(['success' => false, 'message' => 'Konfirmasi password tidak cocok.']);
            exit;
        }

        $userModel = new UserModel();
        $result    = $userModel->updatePassword($userId, $password);

        if ($result) {
            unset($_SESSION['reset_user_id']);
            echo json_encode(['success' => true, 'message' => 'Password changed successfully. Please login.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save password. Please try again.']);
        }
        exit;
    }
}
?>
