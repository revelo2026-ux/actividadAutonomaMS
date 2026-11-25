<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Panel de Gestión de Citas</h3>
        <p class="text-muted small mb-0">Vista adaptada al rol: <strong><?= htmlspecialchars($usuario['rol']) ?></strong></p>
    </div>

    <?php if ($usuario['rol'] === 'cliente'): ?>
        <div class="d-flex gap-2">
            <!-- TODO [Sprint 2 - RF02]: Conectar este botón a un modal o vista para registrar mascota -->
            <button class="btn btn-outline-primary btn-sm" onclick="mostrarAlertaSprint('Sprint 2 - RF02: Formulario de nueva mascota')">
                <i class="bi bi-plus-circle me-1"></i> Nueva Mascota
            </button>
            <!-- TODO [Sprint 2 - RF03]: Conectar este botón a un modal o vista para agendar cita -->
            <button class="btn btn-primary btn-sm" onclick="mostrarAlertaSprint('Sprint 2 - RF03: Formulario para solicitar cita')">
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
                <p class="text-muted mb-0">No tienes mascotas registradas aún.</p>
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
        <!-- TODO [Sprint 4]: Activar filtro dinámico en JS por estado -->
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
                        <tr data-estado="<?= htmlspecialchars($c['estado']) ?>">
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
                                    <!-- TODO [Sprint 3 - RF05]: Conectar botones de confirmar / cancelar -->
                                    <button class="btn btn-sm btn-outline-success" onclick="mostrarAlertaSprint('Sprint 3 - RF05: Confirmar cita #<?= $c['id'] ?>')">Confirmar</button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="mostrarAlertaSprint('Sprint 3 - RF05: Cancelar cita #<?= $c['id'] ?>')">Cancelar</button>
                                <?php elseif ($usuario['rol'] === 'veterinario'): ?>
                                    <!-- TODO [Sprint 3 - RF06]: Conectar modal para registrar diagnóstico y marcar atendida -->
                                    <button class="btn btn-sm btn-outline-primary" onclick="mostrarAlertaSprint('Sprint 3 - RF06: Atender cita #<?= $c['id'] ?>')">Atender</button>
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

<?php require __DIR__ . '/../layouts/footer.php'; ?>
