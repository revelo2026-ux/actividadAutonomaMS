# VetAgenda - Proyecto Base (PHP MVC + JS + HTML + Bootstrap 5)

Proyecto base diseñado para prácticas de **Metodologías de Desarrollo de Software** (Sprints diarios de 2 horas).

## Estructura del Proyecto (Patrón MVC)
```text
vetagenda_php_mvc/
├── database/
│   └── schema.sql               # Script MySQL/MariaDB con tablas y datos semilla
├── app/
│   ├── config/
│   │   └── Database.php         # Conexión PDO a MySQL
│   ├── models/
│   │   ├── Usuario.php          # Modelo de usuarios
│   │   ├── Mascota.php          # Modelo de mascotas (Base para Sprint 2)
│   │   └── Cita.php             # Modelo de citas
│   ├── controllers/
│   │   ├── AuthController.php   # Manejo de login y cierre de sesión
│   │   └── CitaController.php   # Controlador principal del panel y citas
│   └── views/
│       ├── layouts/
│       │   ├── header.php       # Cabecera HTML + Bootstrap 5
│       │   └── footer.php       # Pie de página + JS
│       ├── auth/
│       │   └── login.php        # Vista de inicio de sesión
│       └── dashboard/
│           └── index.php        # Panel principal según rol
└── public/
    ├── index.php                # Front Controller (Enrutador simple)
    ├── css/
    │   └── styles.css           # Estilos personalizados
    └── js/
        └── app.js               # Validaciones y lógica frontend
```

## Instalación Rápida (XAMPP / Laragon / WAMP / Docker)
1. Copia la carpeta `vetagenda_php_mvc` dentro de tu servidor web (`htdocs` en XAMPP o `www` en Laragon).
2. Abre **phpMyAdmin** (o tu cliente MySQL) e importa el archivo `database/schema.sql`.
3. Verifica las credenciales de conexión en `app/config/Database.php` (por defecto: host `localhost`, db `vetagenda`, user `root`, pass `""`).
4. Abre en tu navegador: `http://localhost/vetagenda_php_mvc/public/`

## Usuarios de Prueba (Contraseña para todos: `123456`)
* **Recepcionista:** `recepcion@vetagenda.com`
* **Veterinario:** `vet@vetagenda.com`
* **Cliente (Dueño):** `cliente1@vetagenda.com`

## Tareas por Sprint (Guía para Estudiantes)
* **Sprint 1 (Día 1):** Configurar entorno, BD y verificar autenticación (`RF01`).
* **Sprint 2 (Día 2):** Completar CRUD de mascotas (`RF02`) y formulario de solicitud de citas con validación de disponibilidad (`RF03`, `RF04`).
* **Sprint 3 (Día 3):** Implementar acciones de Recepcionista (confirmar/cancelar citas - `RF05`) y Veterinario (atender y guardar nota clínica - `RF06`).
* **Sprint 4 (Día 4):** Añadir filtros dinámicos con JS/PHP por estado o fecha y pruebas finales.
