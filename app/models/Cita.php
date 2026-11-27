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

   public function verificarDisponibilidad($id_veterinario, $fecha_hora) {
        $query = "SELECT COUNT(*) FROM " . $this->table . "
                  WHERE id_veterinario = :id_veterinario
                  AND fecha_hora = :fecha_hora
                  AND estado != 'cancelada'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_veterinario', $id_veterinario);
        $stmt->bindParam(':fecha_hora', $fecha_hora);
        $stmt->execute();
       
        return $stmt->fetchColumn() == 0;
    }

    public function crear($id_mascota, $id_veterinario, $fecha_hora, $motivo) {
        $query = "INSERT INTO " . $this->table . "
                  (id_mascota, id_veterinario, fecha_hora, motivo, estado)
                  VALUES (:id_mascota, :id_veterinario, :fecha_hora, :motivo, 'pendiente')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_mascota', $id_mascota);
        $stmt->bindParam(':id_veterinario', $id_veterinario);
        $stmt->bindParam(':fecha_hora', $fecha_hora);
        $stmt->bindParam(':motivo', $motivo);
       
        return $stmt->execute();
    }

    public function actualizarEstado($id_cita, $estado, $nota_clinica = null) {
        if ($nota_clinica !== null) {
            $query = "UPDATE " . $this->table . "
                      SET estado = :estado, nota_clinica = :nota_clinica
                      WHERE id = :id_cita";
        } else {
            $query = "UPDATE " . $this->table . "
                      SET estado = :estado
                      WHERE id = :id_cita";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id_cita', $id_cita);
       
        if ($nota_clinica !== null) {
            $stmt->bindParam(':nota_clinica', $nota_clinica);
        }

        return $stmt->execute();
    }
}