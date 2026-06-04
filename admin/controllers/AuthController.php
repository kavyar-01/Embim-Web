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

              if (empty($email)) $errors[] = 'Alamat email wajib diisi.';
              if (empty($pass))  $errors[] = 'Password wajib diisi.';

              if (empty($errors)) {
                  $admin = $this->model->findAdminByEmail($email);
                  if (!$admin || !password_verify($pass, $admin['password'])) {
                      $errors[] = 'Email atau password salah, atau akun bukan admin.';
                  } else {
                      session_regenerate_id(true);
                      $_SESSION['admin_id']    = $admin['id'];
                      $_SESSION['admin_name']  = $admin['full_name'];
                      $_SESSION['admin_email'] = $admin['email'];
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
              $phone    = trim($_POST['phone']      ?? '');
              $pass     = $_POST['password']        ?? '';
              $terms    = !empty($_POST['terms']);

              if (empty($fullName))  $errors[] = 'Nama lengkap wajib diisi.';
              if (empty($email))     $errors[] = 'Alamat email wajib diisi.';
              elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
              if (empty($pass))      $errors[] = 'Password wajib diisi.';
              elseif (strlen($pass) < 8)             $errors[] = 'Password minimal 8 karakter.';
              elseif (!preg_match('/[a-z]/', $pass)) $errors[] = 'Password harus mengandung minimal satu huruf kecil.';
              elseif (!preg_match('/[A-Z]/', $pass)) $errors[] = 'Password harus mengandung minimal satu huruf besar.';
              elseif (!preg_match('/[0-9]/', $pass)) $errors[] = 'Password harus mengandung minimal satu angka.';
              if (!$terms) $errors[] = 'Anda harus menyetujui Syarat & Ketentuan.';

              if (empty($errors)) {
                  $result = $this->model->createAdmin([
                      'full_name' => $fullName,
                      'email'     => $email,
                      'password'  => $pass,
                      'phone'     => $phone,
                  ]);
                  if ($result === 'duplicate') {
                      $errors[] = 'Email sudah terdaftar. Silakan gunakan email lain atau login.';
                  } elseif ($result === true) {
                      header('Location: ?page=login&registered=1');
                      exit;
                  } else {
                      $errors[] = 'Pendaftaran gagal. Silakan coba lagi.';
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
  }
  