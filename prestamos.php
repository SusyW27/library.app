<?php
include 'db_config.php';

// Lógica para registrar nuevo Préstamo (Simplificado)
if (isset($_POST['action']) && $_POST['action'] === 'prestar') {
    $isbn = $conn->real_escape_string($_POST['isbn_libro']);
    $usuario_id = (int)$_POST['id_usuario'];
    $fecha_prestamo = date('Y-m-d');
    // Devolución prevista en 14 días
    $fecha_devolucion_prevista = date('Y-m-d', strtotime('+14 days')); 
    
    $sql = "INSERT INTO Prestamos (isbn_libro, id_usuario, fecha_prestamo, fecha_devolucion_prevista) 
            VALUES ('$isbn', $usuario_id, '$fecha_prestamo', '$fecha_devolucion_prevista')";
    $conn->query($sql);
    header('Location: prestamos.php');
    exit;
}

// Lógica para marcar Devolución
if (isset($_POST['action']) && $_POST['action'] === 'devolver') {
    $id_prestamo = (int)$_POST['id_prestamo'];
    $fecha_devolucion_real = date('Y-m-d');
    
    $sql = "UPDATE Prestamos SET fecha_devolucion_real = '$fecha_devolucion_real' WHERE id_prestamo = $id_prestamo";
    $conn->query($sql);
    header('Location: prestamos.php');
    exit;
}

// Consulta para visualizar Préstamos ACTIVOS (fecha_devolucion_real IS NULL)
$sql_activos = "SELECT 
    p.id_prestamo, l.titulo AS libro_titulo, CONCAT(u.nombre, ' ', u.apellido) AS usuario_nombre,
    p.fecha_prestamo, p.fecha_devolucion_prevista
FROM Prestamos p
JOIN Libros l ON p.isbn_libro = l.isbn
JOIN Usuarios u ON p.id_usuario = u.id_usuario
WHERE p.fecha_devolucion_real IS NULL
ORDER BY p.fecha_prestamo DESC";

$prestamos_activos = $conn->query($sql_activos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Préstamos</title>
</head>
<body>
    <h1>Gestión de Préstamos</h1>

    <h2>Registrar Nuevo Préstamo</h2>
    <form method="POST" action="prestamos.php">
        <input type="hidden" name="action" value="prestar">
        <label>ISBN Libro:</label><input type="text" name="isbn_libro" placeholder="Ej: 978-XXXX" required><br>
        <label>ID Usuario:</label><input type="number" name="id_usuario" placeholder="Ej: 1" required><br>
        <button type="submit">Prestar Libro</button>
        <p>*(Asegúrate que el ISBN y el ID de Usuario existan en sus respectivas tablas.)</p>
    </form>

    <hr>

    <h2>Préstamos Activos</h2>
    <table border="1">
        <tr>
            <th>ID Préstamo</th><th>Libro</th><th>Usuario</th>
            <th>F. Préstamo</th><th>F. Devolución Prevista</th><th>Acción</th>
        </tr>
        <?php while ($row = $prestamos_activos->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id_prestamo']; ?></td>
            <td><?php echo $row['libro_titulo']; ?></td>
            <td><?php echo $row['usuario_nombre']; ?></td>
            <td><?php echo $row['fecha_prestamo']; ?></td>
            <td><?php echo $row['fecha_devolucion_prevista']; ?></td>
            <td>
                <form method="POST" action="prestamos.php" style="display:inline;">
                    <input type="hidden" name="action" value="devolver">
                    <input type="hidden" name="id_prestamo" value="<?php echo $row['id_prestamo']; ?>">
                    <button type="submit" style="background-color: lightgreen;">Marcar como Devuelto</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>