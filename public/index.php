<?php
session_start();

require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/CitaController.php';

$database = new Database();
$db = $database->getConnection();

$action = $_GET['action'] ?? 'dashboard';

$authController = new AuthController($db);
$citaController = new CitaController($db);

switch ($action) {
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'dashboard':
    default:
        $citaController->dashboard();
        break;
}
