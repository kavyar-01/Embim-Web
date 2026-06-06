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
                $error = 'Your name, email address, and password are required';

            } elseif (strlen($fullName) < 4) {
                $error = 'Full name must be at least 4 characters.';

            } elseif (preg_match('/[0-9]/', $fullName)) {
                $error = 'Full name must not contain numbers.';

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format.';

            } elseif (!empty($phoneRaw) && !preg_match('/^[0-9]+$/', $phoneRaw)) {
                $error = 'Phone numbers must consist only of numbers.';

            } elseif (!empty($phoneRaw) && (strlen($phoneRaw) < 7 || strlen($phoneRaw) > 13)) {
                $error = 'Invalid phone number (7–13 digits).';

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'The email format is invalid.';

            } elseif (strlen($password) < 8) {
                $error = 'The password must be at least 8 characters long.';

            } elseif (!preg_match('/[a-z]/', $password)) {
                $error = 'The password must contain at least one lowercase letter.';

            } elseif (!preg_match('/[A-Z]/', $password)) {
                $error = 'The password must contain at least one uppercase letter.';

            } elseif (!preg_match('/[0-9]/', $password)) {
                $error = 'The password must contain at least one number.';

            } elseif (!$agree) {
                $error = 'You must agree to the Terms & Conditions to continue.';

            } else {
                $userModel = new UserModel();
                $result    = $userModel->register($fullName, $email, $phone, $password);

                if ($result['success']) {
                    $_SESSION['flash_success'] = 'Your account has been successfully created! Please log in.';
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