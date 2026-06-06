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
            echo json_encode(['success' => false, 'message' => 'Phone number must only contain numbers.']);
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
            echo json_encode(['success' => false, 'message' => 'Full name or phone number not found.']);
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
            echo json_encode(['success' => false, 'message' => 'Invalid session. Please verify again.']);
            exit;
        }

        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
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
            echo json_encode(['success' => false, 'message' => 'Password confirmation does not match.']);
            exit;
        }

        $userModel = new UserModel();
        $result    = $userModel->updatePassword($userId, $password);

        if ($result) {
            unset($_SESSION['reset_user_id']);
            echo json_encode(['success' => true, 'message' => 'Password successfully changed. Please login.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save password. Please try again.']);
        }
        exit;
    }
}
?>
