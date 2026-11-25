<?php
require_once __DIR__ . '/../models/Cita.php';
require_once __DIR__ . '/../models/Mascota.php';
require_once __DIR__ . '/../models/Usuario.php';

class CitaController {
    private $citaModel;
    private $mascotaModel;
    private $usuarioModel;

    public function __construct($db) {
        $this->citaModel = new Cita($db);
        $this->mascotaModel = new Mascota($db);
        $this->usuarioModel = new Usuario($db);
    }

    public function dashboard() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $usuario = $_SESSION['usuario'];
        $veterinarios = $this->usuarioModel->listarVeterinarios();

        if ($usuario['rol'] === 'cliente') {
            $citas = $this->citaModel->listarPorDueno($usuario['id']);
            $mascotas = $this->mascotaModel->listarPorDueno($usuario['id']);
        } else {
            $citas = $this->citaModel->listarTodas();
            $mascotas = [];
        }

        require __DIR__ . '/../views/dashboard/index.php';
    }

    // TODO [Sprint 2 - RF03]: Método agendarCita()
    // TODO [Sprint 3 - RF05/RF06]: Método cambiarEstadoCita()
}
