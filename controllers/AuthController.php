<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class AuthController {

    private $adminModel;

    public function __construct() {
        $this->adminModel = new AdminModel();
    }

    // Tampilkan halaman login
    public function login() {
        if (isLoggedIn()) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        require_once __DIR__ . '/../views/login.php';
    }

    // Proses login
    public function prosesLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Username dan password wajib diisi.';
            header('Location: index.php?page=login');
            exit;
        }

        $admin = $this->adminModel->cariByUsername($username);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_nama'] = $admin['nama'];
            $_SESSION['loggedin']   = true;
            header('Location: index.php?page=dashboard');
            exit;
        }

        $_SESSION['error'] = 'Username atau password salah.';
        header('Location: index.php?page=login');
        exit;
    }

    // Logout
    public function logout() {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
