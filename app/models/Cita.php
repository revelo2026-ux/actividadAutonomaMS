<?php
class Cita {
    private $conn;
    private $table = 'citas';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listarTodas() {
        $query = "SELECT c.*, m.nombre AS mascota, m.especie, u.nombre AS dueno, v.nombre AS veterinario
                  FROM " . $this->table . " c
                  INNER JOIN mascotas m ON c.id_mascota = m.id
                  INNER JOIN usuarios u ON m.id_dueno = u.id
                  INNER JOIN usuarios v ON c.id_veterinario = v.id
                  ORDER BY c.fecha_hora DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listarPorDueno($id_dueno) {
        $query = "SELECT c.*, m.nombre AS mascota, m.especie, v.nombre AS veterinario
                  FROM " . $this->table . " c
                  INNER JOIN mascotas m ON c.id_mascota = m.id
                  INNER JOIN usuarios v ON c.id_veterinario = v.id
                  WHERE m.id_dueno = :id_dueno
                  ORDER BY c.fecha_hora DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_dueno', $id_dueno);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // TODO [Sprint 2 - RF04]: Implementar verificarDisponibilidad($id_veterinario, $fecha_hora)
    // TODO [Sprint 2 - RF03]: Implementar crear($id_mascota, $id_veterinario, $fecha_hora, $motivo)
    // TODO [Sprint 3 - RF05/RF06]: Implementar actualizarEstado($id_cita, $estado, $nota_clinica = null)
}
