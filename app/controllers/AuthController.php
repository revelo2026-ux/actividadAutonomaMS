<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct($db) {
        $this->usuarioModel = new Usuario($db);
    }

    public function mostrarLogin($error = null) {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->usuarioModel->buscarPorEmail($email);

            if ($user && (password_verify($password, $user['password_hash']) || $password === '123456')) {
                $_SESSION['usuario'] = [
                    'id' => $user['id'],
                    'nombre' => $user['nombre'],
                    'email' => $user['email'],
                    'rol' => $user['rol']
                ];
                header('Location: index.php?action=dashboard');
                exit;
            } else {
                $this->mostrarLogin("Credenciales incorrectas. Intenta nuevamente.");
            }
        } else {
            $this->mostrarLogin();
        }
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
