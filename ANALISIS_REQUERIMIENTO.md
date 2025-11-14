*library.app
1. Requerimientos de Registro de Usuarios
RF-001_Registro de Nuevo Usuario: El sistema debe permitir a un usuario no autenticado crear una nueva cuenta proporcionando nombre, apellido, correo electrónico y contraseña.
RF-002_Validación de Email Único: El sistema debe validar que el correo electrónico proporcionado durante el registro no exista ya en la base de datos de usuarios.
RF-003_Almacenamiento Seguro de Contraseña: El sistema debe almacenar la contraseña utilizando un algoritmo de hash seguro (e.g., password_hash() en PHP) en lugar de texto plano.
RF-004_Requisito de Contraseña: El sistema debe exigir que la contraseña cumpla con una política mínima de seguridad (ej: longitud mínima de 8 caracteres).
RF-005_Notificación de Éxito/Error: El sistema debe mostrar un mensaje claro de éxito tras un registro, o mensajes de error específicos si falla la validación (ej: "El email ya está registrado").

2. Requerimiento de Inicio de Sesión
RF-006_Autenticación de Usuario: El sistema debe permitir a un usuario autenticarse proporcionando su correo electrónico y contraseña.
RF-007_Verificación de Contraseña: El sistema debe verificar la contraseña proporcionada contra el hash almacenado en la base de datos (e.g., password_verify() en PHP).
RF-008_Manejo de Sesión: Tras la autenticación exitosa, el sistema debe iniciar una sesión de PHP para mantener el estado de autenticación del usuario.
RF-009_Redirección Post-Login: Tras el inicio de sesión, el usuario debe ser redirigido a una página de inicio (ej: el panel de préstamos).
RF-010_Manejo de Fallos de Login: El sistema debe mostrar un mensaje genérico de error ante credenciales inválidas (ej: "Credenciales incorrectas") para evitar ataques de fuerza bruta.

3. Requerimiento de Inicio de Sesión
RF-011_Cierre de Sesión: El sistema debe proporcionar una opción para que el usuario destruya su sesión activa y regrese a la página de login.

4. Requerimiento de Arquitectura y Estructura
RF-012_Separación de Capas (MVC Básico): La aplicación debe estructurarse separando claramente: Presentación (archivos HTML/PHP con solo la vista), Lógica de Negocio (validaciones y flujo de la aplicación) y Acceso a Datos (consultas SQL).
RF-013_Módulo de Conexión Centralizado: La conexión a la base de datos (db_config.php) debe ser un archivo único y centralizado para que cualquier cambio en la configuración se aplique globalmente.
RF-014_Abstracción de Datos (DAO/Repository): Se debe crear una clase/archivo de manejo de datos (ej: UsuarioModel.php, LibroRepository.php) para encapsular todas las consultas SQL relacionadas con una entidad. Ejemplo: Una función LibroRepository::findAll() o UsuarioModel::create().
RF-015_Uso de Sentencias Preparadas: Todas las interacciones con la base de datos que involucren datos introducidos por el usuario (Registro, CRUD) deben usar sentencias preparadas de MySQLi (o PDO) para prevenir ataques de Inyección SQL.
RF-016_Control de Acceso a CRUD: Las páginas de gestión CRUD (Libros, Autores, Prestamos) deben validar si existe una sesión activa y redirigir al usuario al login si no está autenticado.

5. Lógica de Negocio de Préstamos (Ejemplo)
RF-017_Validación de Disponibilidad de Libro: Antes de registrar un nuevo préstamo, el sistema debe verificar que el libro con el ISBN solicitado no esté ya prestado (es decir, que no exista un registro en Prestamos donde isbn_libro coincida y fecha_devolucion_real sea NULL).
RF-018_Cálculo de Fecha Prevista: El sistema debe calcular automáticamente la fecha_devolucion_prevista (ej: 14 días después de la fecha_prestamo) al registrar un nuevo préstamo.
RF-019_Gestión de Devolución: La acción de devolver un libro solo debe requerir la actualización del campo fecha_devolucion_real con la fecha actual.
