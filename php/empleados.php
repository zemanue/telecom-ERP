<?php
include('conexion_be.php'); // Incluye la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados - ERP</title>
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
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("INSERT INTO empleados (nombre, email, telefono) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $email, $telefono);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: empleados.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        ?>

        <h2>Agregar Empleado</h2>
        <form method="POST" action="empleados.php?accion=crear">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="email" placeholder="Email">
            <input type="text" name="telefono" placeholder="Teléfono">
            <button type="submit">Agregar</button>
        </form>
        <div class="volver-a" style="text-align: center; margin-top: 10px;">
            <a href="empleados.php" class="menu-item">Volver a Empleados</a>
        </div>

        <?php
    } elseif ($accion == 'editar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo && $_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar datos del formulario
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("UPDATE empleados SET nombre=?, email=?, telefono=? WHERE codigo=?");
            $stmt->bind_param("sssi", $nombre, $email, $telefono, $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: empleados.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            // Mostrar formulario con datos existentes
            $stmt = $conexion->prepare("SELECT * FROM empleados WHERE codigo=?");
            $stmt->bind_param("i", $codigo);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $empleado = $resultado->fetch_assoc();
            ?>

            <h2>Editar Empleado</h2>
            <form method="POST" action="empleados.php?accion=editar&codigo=<?php echo $codigo; ?>">
                <input type="text" name="nombre" value="<?php echo $empleado['nombre']; ?>" required>
                <input type="email" name="email" value="<?php echo $empleado['email']; ?>">
                <input type="text" name="telefono" value="<?php echo $empleado['telefono']; ?>">
                <button type="submit">Actualizar</button>
            </form>

            <?php
        }
    } elseif ($accion == 'eliminar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo) {
            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("DELETE FROM empleados WHERE codigo=?");
            $stmt->bind_param("i", $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: empleados.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        // Listar empleados (acción predeterminada)
        $sql = "SELECT * FROM empleados";
        $resultado = mysqli_query($conexion, $sql);

        echo "<h1>Lista de Empleados</h1>";
        echo "<a href='empleados.php?accion=crear' class='menu-item'>Agregar Empleado</a>";
        echo "<table border='1'>";
        echo "<tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>";

        // Iterar sobre los resultados y mostrarlos en la tabla
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "<tr>
                <td>{$fila['codigo']}</td>
                <td>{$fila['nombre']}</td>
                <td>{$fila['email']}</td>
                <td>{$fila['telefono']}</td>
                <td>
                    <a href='empleados.php?accion=editar&codigo={$fila['codigo']}'>Editar</a> |
                    <a href='empleados.php?accion=eliminar&codigo={$fila['codigo']}' 
                        onclick='return confirm(\"¿Estás seguro de eliminar este empleado?\");'>Eliminar</a>
                </td>
            </tr>";
        }

        echo "</table>";
    }

    // Cierra la conexión
    $conexion->close();
    ?>
</body>

</html>