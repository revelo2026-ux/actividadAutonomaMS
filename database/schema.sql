CREATE DATABASE IF NOT EXISTS vetagenda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vetagenda;

DROP TABLE IF EXISTS citas;
DROP TABLE IF EXISTS mascotas;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'recepcionista', 'veterinario') NOT NULL DEFAULT 'cliente',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE mascotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    especie VARCHAR(50) NOT NULL,
    raza VARCHAR(80) DEFAULT 'Mestizo',
    edad INT DEFAULT 0,
    id_dueno INT NOT NULL,
    FOREIGN KEY (id_dueno) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mascota INT NOT NULL,
    id_veterinario INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    motivo VARCHAR(255) NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada', 'atendida') NOT NULL DEFAULT 'pendiente',
    nota_clinica TEXT NULL,
    FOREIGN KEY (id_mascota) REFERENCES mascotas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_veterinario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Datos semilla (La contraseña hash corresponde a: 123456)
INSERT INTO usuarios (id, nombre, email, password_hash, rol) VALUES
(1, 'Laura Gómez (Recepción)', 'recepcion@vetagenda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'recepcionista'),
(2, 'Dr. Carlos Mendoza', 'vet@vetagenda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'veterinario'),
(3, 'Ana Torres', 'cliente1@vetagenda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente'),
(4, 'Luis Paredes', 'cliente2@vetagenda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente');

INSERT INTO mascotas (id, nombre, especie, raza, edad, id_dueno) VALUES
(1, 'Max', 'Perro', 'Labrador', 3, 3),
(2, 'Luna', 'Gato', 'Siamés', 2, 3),
(3, 'Rocky', 'Perro', 'Bulldog', 4, 4);

INSERT INTO citas (id_mascota, id_veterinario, fecha_hora, motivo, estado, nota_clinica) VALUES
(1, 2, DATE_ADD(NOW(), INTERVAL 1 DAY), 'Vacunación anual', 'confirmada', NULL),
(2, 2, DATE_ADD(NOW(), INTERVAL 2 DAY), 'Revisión general y desparasitación', 'pendiente', NULL),
(3, 2, DATE_SUB(NOW(), INTERVAL 1 DAY), 'Control dermatológico', 'atendida', 'Se receta shampoo medicado cada 7 días.');
