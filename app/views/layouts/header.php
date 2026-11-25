<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VetAgenda - Gestión de Citas Veterinarias</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?action=dashboard">
            <i class="bi bi-heart-pulse-fill me-2"></i>VetAgenda
        </a>
        <?php if (isset($_SESSION['usuario'])): ?>
            <div class="d-flex align-items-center text-white">
                <span class="me-3 small">
                    <i class="bi bi-person-circle me-1"></i>
                    <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
                    <span class="badge bg-light text-primary ms-1 text-uppercase">
                        <?= htmlspecialchars($_SESSION['usuario']['rol']) ?>
                    </span>
                </span>
                <a href="index.php?action=logout" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<main class="container my-4 flex-grow-1">
