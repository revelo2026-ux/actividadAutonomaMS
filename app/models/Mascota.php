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

    public function crear($nombre, $especie, $raza, $edad, $id_dueno) {
        $query = "INSERT INTO mascotas (nombre, especie, raza, edad, id_dueno)
                  VALUES (:nombre, :especie, :raza, :edad, :id_dueno)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':especie', $especie);
        $stmt->bindParam(':raza', $raza);
        $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
        $stmt->bindParam(':id_dueno', $id_dueno, PDO::PARAM_INT);
       
        return $stmt->execute();
    }
}
