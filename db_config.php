<?php
// Configuración de la conexión a la base de datos
define('DB_SERVER', 'localhost'); //Host de la base de datos
define('DB_USERNAME', 'root');//Nombre de usuario para la conxión
define('DB_PASSWORD', ''); //Nombre de usuario para la conxión
define('DB_DATABASE', 'biblioteca_db'); //Nombre de labase de datos del proyecto

// Conexión_Se utiliza en todos los módulos
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Verificar la conexión_Termina la ejecucuión si la conexión falla
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Opcional: Función para crear la estructura de la base de datos si no existe
function setupDatabase($conn) {
    // 1. Crear tabla Autores
    $sql_autores = "CREATE TABLE IF NOT EXISTS Autores (
        id_autor INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        nacionalidad VARCHAR(50)
    )";
    $conn->query($sql_autores);

    // 2. Crear tabla Libros (FK a Autores)
    $sql_libros = "CREATE TABLE IF NOT EXISTS Libros (
        isbn VARCHAR(20) PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        anio_publicacion YEAR,
        id_autor INT,
        FOREIGN KEY (id_autor) REFERENCES Autores(id_autor) ON DELETE CASCADE
    )";
    $conn->query($sql_libros);

    // 3. Crear tabla Usuarios (Simple para Préstamos)
    $sql_usuarios = "CREATE TABLE IF NOT EXISTS Usuarios (
        id_usuario INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        apellido VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE
    )";
    $conn->query($sql_usuarios);
    
    // 4. Crear tabla Prestamos (FK a Libros y Usuarios)
    $sql_prestamos = "CREATE TABLE IF NOT EXISTS Prestamos (
        id_prestamo INT AUTO_INCREMENT PRIMARY KEY,
        isbn_libro VARCHAR(20) NOT NULL,
        id_usuario INT NOT NULL,
        fecha_prestamo DATE NOT NULL,
        fecha_devolucion_prevista DATE NOT NULL,
        fecha_devolucion_real DATE NULL,
        FOREIGN KEY (isbn_libro) REFERENCES Libros(isbn) ON DELETE CASCADE,
        FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
    )";
    $conn->query($sql_prestamos);

    // Nota: Es recomendable ejecutar setupDatabase($conn); una vez al inicio del proyecto.
}

//setupDatabase($conn); // Ejecutar la función una vez
?>