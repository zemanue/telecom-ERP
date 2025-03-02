<?php
include('conexion_be.php'); // Incluye la conexión a la base de datos

// Incluir el archivo CSS externo
echo '<link rel="stylesheet" type="text/css" href="../assets/css/estilos_menu.css">';

// Mostrar botón para regresar al menú principal
echo '<a href="home.php">Volver al Menú</a>';

// Determinar la acción a realizar basada en el parámetro 'accion' de la URL
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

if ($accion == 'crear') {
    // Mostrar formulario para crear un empleado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Procesar datos del formulario
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];

        $sql = "INSERT INTO empleados (nombre, email, telefono) 
                VALUES ('$nombre', '$email', '$telefono')";
        if (mysqli_query($conexion, $sql)) {
            header("Location: empleados.php"); // Redirigir al listado
            exit();
        } else {
            echo "Error: " . mysqli_error($conexion);
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
    <?php
} elseif ($accion == 'editar') {
    // Editar empleado existente
    $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
    if ($codigo && $_SERVER['REQUEST_METHOD'] == 'POST') {
        // Procesar datos del formulario
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];

        $sql = "UPDATE empleados SET nombre='$nombre', email='$email', telefono='$telefono' WHERE codigo=$codigo";
        if (mysqli_query($conexion, $sql)) {
            header("Location: empleados.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    } else {
        // Mostrar formulario con datos existentes
        $sql = "SELECT * FROM empleados WHERE codigo=$codigo";
        $resultado = mysqli_query($conexion, $sql);
        $empleado = mysqli_fetch_assoc($resultado);
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
    // Eliminar empleado
    $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
    if ($codigo) {
        $sql = "DELETE FROM empleados WHERE codigo=$codigo";
        if (mysqli_query($conexion, $sql)) {
            header("Location: empleados.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    }
} else {
    // Listar empleados (acción predeterminada)
    $sql = "SELECT * FROM empleados";
    $resultado = mysqli_query($conexion, $sql);

    echo "<h1>Lista de Empleados</h1>";
    echo "<a href='empleados.php?accion=crear' style='margin: 10px 0; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;'>Agregar Empleado</a>";
    echo "<table border='1'>";
    echo "<tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Teléfono</th>
        <th>Acciones</th>
    </tr>";

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
mysqli_close($conexion);
?>
