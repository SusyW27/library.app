<?php
include 'db_config.php'; // Incluir la conexión a la base de datos

// --- LÓGICA CRUD ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Sanitización y obtención de datos comunes
    $isbn = $conn->real_escape_string($_POST['isbn']);
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $anio = (int)$_POST['anio_publicacion'];
    $id_autor = (int)$_POST['id_autor'];

    if ($action === 'create') {
        // Crear un nuevo libro
        $sql = "INSERT INTO Libros (isbn, titulo, anio_publicacion, id_autor) 
                VALUES ('$isbn', '$titulo', $anio, $id_autor)";
    } elseif ($action === 'update') {
        // Actualizar un libro existente
        $old_isbn = $conn->real_escape_string($_POST['old_isbn']);
        $sql = "UPDATE Libros 
                SET isbn='$isbn', titulo='$titulo', anio_publicacion=$anio, id_autor=$id_autor 
                WHERE isbn='$old_isbn'";
    } elseif ($action === 'delete') {
        // Eliminar un libro
        $isbn_to_delete = $conn->real_escape_string($_POST['isbn_delete']);
        $sql = "DELETE FROM Libros WHERE isbn='$isbn_to_delete'";
    }

    if (isset($sql)) {
        if ($conn->query($sql) === TRUE) {
            // Éxito
        } else {
            // Manejo básico de errores (ej: ISBN duplicado)
            echo "Error: " . $conn->error;
        }
    }

    // Redirigir para limpiar el POST y evitar reenvío de formulario
    header('Location: libros.php'); 
    exit;
}

// --- LECTURA DE DATOS PARA FORMULARIO Y TABLA ---

// 1. Obtener la lista de Autores para el campo de selección (dropdown)
$autores_result = $conn->query("SELECT id_autor, nombre FROM Autores ORDER BY nombre");

// 2. Obtener la lista de Libros (con el nombre del autor)
$sql_libros = "SELECT 
    L.isbn, L.titulo, L.anio_publicacion, A.nombre AS nombre_autor, L.id_autor
    FROM Libros L
    JOIN Autores A ON L.id_autor = A.id_autor 
    ORDER BY L.titulo";
$libros_result = $conn->query($sql_libros);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Libros</title>
</head>
<body>
    <h1>📚 Gestión de Libros</h1>
    <p><a href="index.html">Volver al Inicio</a></p>

    <hr>

    <h2>Registrar Nuevo Libro</h2>
    <form method="POST" action="libros.php">
        <input type="hidden" name="action" value="create">
        
        <label>ISBN:</label>
        <input type="text" name="isbn" required placeholder="Ej: 978-0321765723"><br><br>
        
        <label>Título:</label>
        <input type="text" name="titulo" required><br><br>
        
        <label>Año de Publicación:</label>
        <input type="number" name="anio_publicacion" min="1000" max="<?php echo date('Y'); ?>" required><br><br>
        
        <label>Autor:</label>
        <select name="id_autor" required>
            <option value="">-- Seleccione un Autor --</option>
            <?php 
            // Rellenar el Select con los Autores
            if ($autores_result->num_rows > 0):
                while ($autor = $autores_result->fetch_assoc()): ?>
                    <option value="<?php echo $autor['id_autor']; ?>">
                        <?php echo $autor['nombre']; ?>
                    </option>
                <?php endwhile;
            else: ?>
                 <option value="" disabled>No hay autores registrados</option>
            <?php endif; ?>
        </select><br><br>
        
        <button type="submit">Guardar Libro</button>
    </form>

    <hr>

    <h2>Lista de Libros</h2>
    <table border="1">
        <tr>
            <th>ISBN</th>
            <th>Título</th>
            <th>Año</th>
            <th>Autor</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $libros_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['isbn']; ?></td>
            <td><?php echo $row['titulo']; ?></td>
            <td><?php echo $row['anio_publicacion']; ?></td>
            <td>**<?php echo $row['nombre_autor']; ?>**</td>
            <td>
                <form method="POST" action="libros.php" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="isbn_delete" value="<?php echo $row['isbn']; ?>">
                    <button type="submit" onclick="return confirm('¿Seguro que desea eliminar el libro?');">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>