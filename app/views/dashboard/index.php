<?php require __DIR__ . '/../layouts/header.php'; ?>

<!-- Alertas de retroalimentación (Éxito / Error) -->
<?php if (isset($_SESSION['exito'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['exito']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['exito']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Panel de Gestión de Citas</h3>
        <p class="text-muted small mb-0">Vista adaptada al rol: <strong><?= htmlspecialchars($usuario['rol']) ?></strong></p>
    </div>

    <?php if ($usuario['rol'] === 'cliente'): ?>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaMascota">
                <i class="bi bi-plus-circle me-1"></i> Nueva Mascota
            </button>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgendarCita" <?= empty($mascotas) ? 'disabled' : '' ?>>
                <i class="bi bi-calendar-plus me-1"></i> Solicitar Cita
            </button>
        </div>
    <?php endif; ?>
</div>

<?php if ($usuario['rol'] === 'cliente'): ?>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-paw me-1 text-primary"></i> Mis Mascotas Registradas
        </div>
        <div class="card-body">
            <?php if (empty($mascotas)): ?>
                <p class="text-muted mb-0">No tienes mascotas registradas aún. Registra una para poder solicitar citas.</p>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($mascotas as $m): ?>
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="fw-bold mb-1"><?= htmlspecialchars($m['nombre']) ?></h6>
                                <span class="badge bg-secondary"><?= htmlspecialchars($m['especie']) ?></span>
                                <span class="small text-muted ms-1"><?= htmlspecialchars($m['raza']) ?> • <?= (int)$m['edad'] ?> años</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-calendar-check me-1 text-primary"></i> Listado de Citas</span>
        <select id="filtroEstado" class="form-select form-select-sm w-auto">
            <option value="todas">Todos los estados</option>
            <option value="pendiente">Pendiente</option>
            <option value="confirmada">Confirmada</option>
            <option value="atendida">Atendida</option>
            <option value="cancelada">Cancelada</option>
        </select>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tablaCitas">
            <thead class="table-light">
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Mascota</th>
                    <?php if ($usuario['rol'] !== 'cliente'): ?>
                        <th>Dueño</th>
                    <?php endif; ?>
                    <th>Veterinario</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Acciones / Nota</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($citas)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No hay citas registradas.</td></tr>
                <?php else: ?>
                    <?php foreach ($citas as $c): ?>
                        <?php
                            $badgeClass = match($c['estado']) {
                                'pendiente' => 'bg-warning text-dark',
                                'confirmada' => 'bg-info text-dark',
                                'atendida' => 'bg-success',
                                'cancelada' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        ?>
                        <tr class="fila-cita" data-estado="<?= htmlspecialchars($c['estado']) ?>">
                            <td><?= date('d/m/Y H:i', strtotime($c['fecha_hora'])) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($c['mascota']) ?></strong>
                                <div class="small text-muted"><?= htmlspecialchars($c['especie']) ?></div>
                            </td>
                            <?php if ($usuario['rol'] !== 'cliente'): ?>
                                <td><?= htmlspecialchars($c['dueno']) ?></td>
                            <?php endif; ?>
                            <td><?= htmlspecialchars($c['veterinario']) ?></td>
                            <td><?= htmlspecialchars($c['motivo']) ?></td>
                            <td><span class="badge <?= $badgeClass ?> text-uppercase"><?= htmlspecialchars($c['estado']) ?></span></td>
                            <td>
                                <?php if ($usuario['rol'] === 'recepcionista'): ?>
                                    <?php if ($c['estado'] === 'pendiente'): ?>
                                        <form action="index.php?action=cambiarEstadoCita" method="POST" class="d-inline">
                                            <input type="hidden" name="id_cita" value="<?= $c['id'] ?>">
                                            <input type="hidden" name="estado" value="confirmada">
                                            <button type="submit" class="btn btn-sm btn-outline-success">Confirmar</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($c['estado'] === 'pendiente' || $c['estado'] === 'confirmada'): ?>
                                        <form action="index.php?action=cambiarEstadoCita" method="POST" class="d-inline">
                                            <input type="hidden" name="id_cita" value="<?= $c['id'] ?>">
                                            <input type="hidden" name="estado" value="cancelada">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($c['estado'] === 'atendida' || $c['estado'] === 'cancelada'): ?>
                                        <span class="small text-muted">Sin acciones</span>
                                    <?php endif; ?>

                                <?php elseif ($usuario['rol'] === 'veterinario'): ?>
                                    <?php if ($c['estado'] === 'confirmada' || $c['estado'] === 'pendiente'): ?>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAtender<?= $c['id'] ?>">
                                            Atender
                                        </button>
                                    <?php else: ?>
                                        <span class="small text-muted"><?= htmlspecialchars($c['nota_clinica'] ?? 'Sin notas') ?></span>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <span class="small text-muted"><?= htmlspecialchars($c['nota_clinica'] ?? 'Sin notas') ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODALES CLIENTE (Sprint 2 - RF02 & RF03) -->
<?php if ($usuario['rol'] === 'cliente'): ?>
    <!-- Modal Nueva Mascota -->
    <div class="modal fade" id="modalNuevaMascota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="index.php?action=registrarMascota" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nueva Mascota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Especie</label>
                        <select name="especie" class="form-select" required>
                            <option value="Perro">Perro</option>
                            <option value="Gato">Gato</option>
                            <option value="Ave">Ave</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Raza</label>
                                                <input type="text" name="raza" class="form-control" placeholder="Ej. Mestizo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Edad (años)</label>
                        <input type="number" name="edad" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Mascota</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalAgendarCita" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="index.php?action=agendarCita" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Solicitar Nueva Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mascota</label>
                        <select name="id_mascota" class="form-select" required>
                            <?php foreach ($mascotas as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?> (<?= htmlspecialchars($m['especie']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Veterinario</label>
                        <select name="id_veterinario" class="form-select" required>
                            <?php foreach ($veterinarios as $v): ?>
                                <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha y Hora</label>
                        <input type="datetime-local" name="fecha_hora" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo</label>
                        <textarea name="motivo" class="form-control" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Solicitar Cita</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php if ($usuario['rol'] === 'veterinario' && !empty($citas)): ?>
    <?php foreach ($citas as $c): ?>
        <?php if ($c['estado'] === 'confirmada' || $c['estado'] === 'pendiente'): ?>
            <div class="modal fade" id="modalAtender<?= $c['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="index.php?action=cambiarEstadoCita" method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Atender Cita - <?= htmlspecialchars($c['mascota']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_cita" value="<?= $c['id'] ?>">
                            <input type="hidden" name="estado" value="atendida">
                            <p class="small text-muted mb-2"><strong>Motivo:</strong> <?= htmlspecialchars($c['motivo']) ?></p>
                            <div class="mb-3">
                                <label class="form-label">Nota clínica</label>
                                <textarea name="nota_clinica" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Marcar como Atendida</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

