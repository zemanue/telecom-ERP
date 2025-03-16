<?php
include('conexion_be.php'); // Incluye la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - ERP</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/estilos_menu.css">
</head>

<body>
    <a href="../index.html" class="logout-btn">Cerrar Sesión</a>
    <a href="home.php" class="menu-item">Volver al Menú</a>

    <?php
    // Determinar la acción a realizar basada en el parámetro 'accion' de la URL
    $accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

    if ($accion == 'crear') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar datos del formulario
            $nombre_almacen = $_POST['nombre_almacen'];
            $ubicacion = $_POST['ubicacion'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("INSERT INTO almacen (nombre_almacen, ubicacion) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("ss", $nombre_almacen, $ubicacion);

                // Ejecutar la consulta y verificar si fue exitosa
                if ($stmt->execute()) {
                    header("Location: almacenes.php"); // Redirigir al listado
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Error: " . $conexion->error;
            }
        }
        ?>

        <h2>Agregar Almacén</h2>
        <form method="POST" action="almacenes.php?accion=crear">
            <input type="text" name="nombre_almacen" placeholder="Nombre del Almacén" required>
            <input type="text" name="ubicacion" placeholder="Ubicación" required>
            <button type="submit">Agregar</button>
        </form>
        <div class="volver-a" style="text-align: center; margin-top: 10px;">
            <a href="almacenes.php" class="menu-item">Volver a Almacenes</a>
        </div>

    <?php } elseif ($accion == 'editar') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar datos del formulario
            $codigo = $_POST['codigo'];
            $nombre_almacen = $_POST['nombre_almacen'];
            $ubicacion = $_POST['ubicacion'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("UPDATE almacen SET nombre_almacen = ?, ubicacion = ? WHERE codigo = ?");
            $stmt->bind_param("ssi", $nombre_almacen, $ubicacion, $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: almacenes.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        ?>

        <h2>Editar Almacén</h2>
        <form method="POST" action="almacenes.php?accion=editar">
            <input type="hidden" name="codigo" value="<?php echo $_GET['codigo']; ?>">
            <input type="text" name="nombre_almacen" placeholder="Nombre del Almacén" required>
            <input type="text" name="ubicacion" placeholder="Ubicación" required>
            <button type="submit">Guardar Cambios</button>
        </form>
        <div class="volver-a" style="text-align: center; margin-top: 10px;">
            <a href="almacenes.php" class="menu-item">Volver a Almacenes</a>
        </div>

    <?php } elseif ($accion == 'eliminar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo) {
            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("DELETE FROM almacen WHERE codigo = ?");
            $stmt->bind_param("i", $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: almacenes.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        // Listar almacenes (acción predeterminada)
        $sql = "SELECT * FROM almacen";
        $resultado = mysqli_query($conexion, $sql);

        echo "<h1>Lista de Almacenes</h1>";
        echo "<a href='almacenes.php?accion=crear' class='menu-item'>Agregar Almacén</a>";
        echo "<table border='1'>";
        echo "<tr>
            <th>Código</th>
            <th>Nombre del Almacén</th>
            <th>Ubicación</th>
            <th>Acciones</th>
        </tr>";

        // Verificar si la consulta fue exitosa
        if ($resultado) {
            // Iterar sobre los resultados y mostrarlos en la tabla
            while ($fila = mysqli_fetch_assoc($resultado)) {
                echo "<tr>
                <td>{$fila['codigo']}</td>
                <td>{$fila['nombre_almacen']}</td>
                <td>{$fila['ubicacion']}</td>
                <td>
                    <a href='almacenes.php?accion=editar&codigo={$fila['codigo']}'>Editar</a> |
                    <a href='almacenes.php?accion=eliminar&codigo={$fila['codigo']}' 
                    onclick='return confirm(\"¿Estás seguro de eliminar este almacén?\");'>Eliminar</a>
                </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay almacenes registrados</td></tr>";
        }

        echo "</table>";
    }

    // Cierra la conexión
    $conexion->close();
    ?>
</body>

</html>