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

        // Sprint 2 - RF02: Registrar nueva mascota
    public function registrarMascota() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
            header('Location: index.php?action=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $especie = trim($_POST['especie'] ?? '');
            $raza = trim($_POST['raza'] ?? 'Mestizo');
            $edad = (int)($_POST['edad'] ?? 0);
            $id_dueno = $_SESSION['usuario']['id'];

            if ($this->mascotaModel->crear($nombre, $especie, $raza, $edad, $id_dueno)) {
                $_SESSION['exito'] = 'Mascota registrada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al registrar la mascota.';
            }

            header('Location: index.php?action=dashboard');
            exit;
        }
    }

    // Sprint 2 - RF03 y RF04: Agendar cita verificando disponibilidad
    public function agendarCita() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_mascota = $_POST['id_mascota'] ?? '';
            $id_veterinario = $_POST['id_veterinario'] ?? '';
            $raw_fecha = $_POST['fecha_hora'] ?? '';
            $fecha_hora = str_replace('T', ' ', $raw_fecha) . ':00';
            $motivo = trim($_POST['motivo'] ?? '');

            if (!$this->citaModel->verificarDisponibilidad($id_veterinario, $fecha_hora)) {
                $_SESSION['error'] = 'El veterinario ya tiene una cita programada en esa fecha y hora.';
                header('Location: index.php?action=dashboard');
                exit;
            }

            if ($this->citaModel->crear($id_mascota, $id_veterinario, $fecha_hora, $motivo)) {
                $_SESSION['exito'] = 'Cita solicitada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al solicitar la cita.';
            }

            header('Location: index.php?action=dashboard');
            exit;
        }
    }

    // Sprint 3 - RF05 y RF06: Confirmar, cancelar o atender cita
    public function cambiarEstadoCita() {
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] === 'cliente') {
            header('Location: index.php?action=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_cita = $_POST['id_cita'] ?? '';
            $estado = $_POST['estado'] ?? '';
            $nota_clinica = isset($_POST['nota_clinica']) && trim($_POST['nota_clinica']) !== '' 
                            ? trim($_POST['nota_clinica']) 
                            : null;

            if ($this->citaModel->actualizarEstado($id_cita, $estado, $nota_clinica)) {
                $_SESSION['exito'] = 'Cita actualizada a estado: ' . strtoupper($estado) . '.';
            } else {
                $_SESSION['error'] = 'No se pudo actualizar el estado de la cita.';
            }

            header('Location: index.php?action=dashboard');
            exit;
        }
    }

}
