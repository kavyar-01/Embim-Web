<?php
  require_once __DIR__ . '/../models/AuthModel.php';

  class AuthController {
      private AuthModel $model;
      public function __construct() { $this->model = new AuthModel(); }

      // ── LOGIN ────────────────────────────────────────────────────────────────
      public function login(): void {
          $errors = []; $old = [];

          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $old   = $_POST;
              $email = trim($_POST['email'] ?? '');
              $pass  = $_POST['password'] ?? '';

              if (empty($email)) $errors[] = 'Email address is required.';
              if (empty($pass))  $errors[] = 'Password is required.';

              if (empty($errors)) {
                  $admin = $this->model->findAdminByEmail($email);
                  if (!$admin || !password_verify($pass, $admin['password'])) {
                      $errors[] = 'Incorrect email or password.';
                  } else {
                      session_regenerate_id(true);
                      $_SESSION['admin_id']    = $admin['id'];
                      $_SESSION['admin_name']  = $admin['full_name'];
                      $_SESSION['admin_email'] = $admin['email'];
                      $_SESSION['admin_role']  = $admin['role'];
                      if (!empty($admin['photo_profile'])) {
                          $_SESSION['admin_photo'] = $admin['photo_profile'];
                      }
                      header('Location: ?page=dashboard');
                      exit;
                  }
              }
          }

          require_once __DIR__ . '/../views/auth/login.php';
      }

      // ── REGISTER ─────────────────────────────────────────────────────────────
      public function register(): void {
          $errors = []; $old = [];

          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $old      = $_POST;
              $fullName = trim($_POST['full_name']  ?? '');
              $email    = trim($_POST['email']      ?? '');
              $rawPhone = trim($_POST['phone'] ?? '');
              $phone    = $rawPhone;
              if ($rawPhone !== '') {
                  $phone = ltrim($rawPhone, '0');
                  $phone = '+62' . $phone;
              }
              $pass     = $_POST['password']        ?? '';
              $terms    = !empty($_POST['terms']);

              if (empty($fullName))  $errors[] = 'Full name is required.';
              elseif (preg_match('/[0-9]/', $fullName)) $errors[] = 'Nama lengkap tidak boleh mengandung angka.';
              if (empty($email))     $errors[] = 'Email address is required.';
              elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
              if (empty($rawPhone))  $errors[] = 'Phone number is required.';
              elseif (strlen($rawPhone) < 7) $errors[] = 'Nomor telepon minimal 7 karakter.';
              if (empty($pass))      $errors[] = 'Password is required.';
              elseif (strlen($pass) < 8)             $errors[] = 'Password minimal 8 karakter.';
              elseif (!preg_match('/[a-z]/', $pass)) $errors[] = 'Password must contain at least one lowercase letter.';
              elseif (!preg_match('/[A-Z]/', $pass)) $errors[] = 'Password must contain at least one uppercase letter.';
              elseif (!preg_match('/[0-9]/', $pass)) $errors[] = 'Password must contain at least one number.';
              if (!$terms) $errors[] = 'You must agree to the Terms & Conditions.';

              if (empty($errors)) {
                  $result = $this->model->createAdmin([
                      'full_name' => $fullName,
                      'email'     => $email,
                      'password'  => $pass,
                      'phone'     => $phone,
                  ]);
                  if ($result === 'duplicate') {
                      $errors[] = 'Email is already registered. Please use another email or login.';
                  } elseif ($result === true) {
                      header('Location: ?page=login&registered=1');
                      exit;
                  } else {
                      $errors[] = 'Registration failed. Please try again.';
                  }
              }
          }

          require_once __DIR__ . '/../views/auth/register.php';
      }

      // ── LOGOUT ───────────────────────────────────────────────────────────────
      public function logout(): void {
          session_unset();
          session_destroy();
          header('Location: ?page=login&logout=1');
          exit;
      }

      // ── FORGOT PASSWORD ──────────────────────────────────────────────────────
      public function forgotVerify(): void {
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

          // Format nomor telepon menjadi +62... agar cocok dengan data di database
          $fullPhone = '+62' . ltrim($phone, '0');

          $admin = $this->model->verifyIdentity($fullName, $fullPhone);

          if ($admin) {
              $_SESSION['reset_admin_id'] = $admin['id'];
              echo json_encode([
                  'success' => true,
                  'user_id' => $admin['id'],
                  'message' => 'Identitas admin terverifikasi.',
              ]);
          } else {
              echo json_encode(['success' => false, 'message' => 'Nama lengkap atau nomor telepon admin tidak ditemukan.']);
          }
          exit;
      }

      public function forgotReset(): void {
          header('Content-Type: application/json');

          if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
              echo json_encode(['success' => false, 'message' => 'Invalid method.']);
              exit;
          }

          $userId   = (int)($_POST['user_id']  ?? 0);
          $password = $_POST['password']        ?? '';
          $confirm  = $_POST['confirm']         ?? '';

          if (!isset($_SESSION['reset_admin_id']) || (int)$_SESSION['reset_admin_id'] !== $userId) {
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

          $result = $this->model->updatePassword($userId, $password);

          if ($result) {
              unset($_SESSION['reset_admin_id']);
              echo json_encode(['success' => true, 'message' => 'Admin password changed successfully. Please login.']);
          } else {
              echo json_encode(['success' => false, 'message' => 'Failed to save password. Please try again.']);
          }
          exit;
      }
  }
  
