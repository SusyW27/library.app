<?php
include 'db_config.php'; // Módulo para la gestión CRUD de la entidad Autor

// Lógica CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';//Define la operación CRUD a realizar
//Propiedades del formulario
    if ($action === 'create' || $action === 'update') {
        $nombre = $conn->real_escape_string($_POST['nombre']);//Nombre del autor a colocar
        $nacionalidad = $conn->real_escape_string($_POST['nacionalidad']);
        //Método Crear y Actualizar (Lógica de NEgocio)
        if ($action === 'create') {
            $sql = "INSERT INTO Autores (nombre, nacionalidad) VALUES ('$nombre', '$nacionalidad')";
        } elseif ($action === 'update') {
            $id = (int)$_POST['id_autor'];
            $sql = "UPDATE Autores SET nombre='$nombre', nacionalidad='$nacionalidad' WHERE id_autor=$id";
        }
        $conn->query($sql);
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id_autor'];//Método Eliminar (Lógica de NEgocio)
        $sql = "DELETE FROM Autores WHERE id_autor=$id";
        $conn->query($sql);
    }
    header('Location: autores.php'); // Redirigir para evitar reenvío de formulario
    exit;
}

// Lectura de Autores_Contiene todos los registros de la tabla Autores
$autores = $conn->query("SELECT * FROM Autores");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Autores</title>
</head>
<body>
    <h1>Gestión de Autores</h1>

    <h2>Crear/Editar Autor</h2>
    <form method="POST" action="autores.php">
        <input type="hidden" name="action" value="create">
        <label>Nombre:</label><input type="text" name="nombre" required><br>
        <label>Nacionalidad:</label><input type="text" name="nacionalidad"><br>
        <button type="submit">Guardar Autor</button>
    </form>

    <hr>

    <h2>Lista de Autores</h2>
    <table border="1">
        <tr><th>ID</th><th>Nombre</th><th>Nacionalidad</th><th>Acciones</th></tr>
        <?php while ($row = $autores->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id_autor']; ?></td>
            <td><?php echo $row['nombre']; ?></td>
            <td><?php echo $row['nacionalidad']; ?></td>
            <td>
                <form method="POST" action="autores.php" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_autor" value="<?php echo $row['id_autor']; ?>">
                    <button type="submit" onclick="return confirm('¿Seguro que desea eliminar?');">Eliminar</button>
                </form>
                </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>