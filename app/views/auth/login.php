<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="card-title text-center mb-4 fw-bold text-primary">Iniciar Sesión</h4>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form id="loginForm" action="index.php?action=login" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@vetagenda.com" required>
                        <div class="invalid-feedback">Ingresa un correo válido.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••" required>
                        <div class="invalid-feedback">La contraseña es obligatoria.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">Ingresar al Sistema</button>
                </form>

                <hr class="my-4">
                <div class="small text-muted">
                    <strong>Accesos de prueba (clave: <code>123456</code>):</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        <li>Recepción: <code>recepcion@vetagenda.com</code></li>
                        <li>Veterinario: <code>vet@vetagenda.com</code></li>
                        <li>Cliente: <code>cliente1@vetagenda.com</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
