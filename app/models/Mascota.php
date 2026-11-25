<?php
class Mascota {
    private $conn;
    private $table = 'mascotas';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listarPorDueno($id_dueno) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_dueno = :id_dueno ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_dueno', $id_dueno);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // TODO [Sprint 2 - RF02]: Implementar método crear($nombre, $especie, $raza, $edad, $id_dueno)
}
