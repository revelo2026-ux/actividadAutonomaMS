document.addEventListener('DOMContentLoaded', () => {
    // Validación Bootstrap del formulario de login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (event) => {
            if (!loginForm.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            loginForm.classList.add('was-validated');
        });
    }

    // Filtro rápido por estado en tabla de citas (Base funcional para Sprint 4)
    const filtroEstado = document.getElementById('filtroEstado');
    const tablaCitas = document.getElementById('tablaCitas');

    if (filtroEstado && tablaCitas) {
        filtroEstado.addEventListener('change', () => {
            const valor = filtroEstado.value;
            const filas = tablaCitas.querySelectorAll('tbody tr[data-estado]');
            filas.forEach(fila => {
                if (valor === 'todas' || fila.dataset.estado === valor) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    }
});

// Función auxiliar para guiar a los estudiantes en los componentes pendientes
function mostrarAlertaSprint(mensaje) {
    alert('Tarea pendiente por desarrollar:\n' + mensaje);
}
