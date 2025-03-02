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
    <a href="../index.php" class="logout-btn">Cerrar Sesión</a>
    <a href="home.php" class="menu-item">Volver al Menú</a>

    <?php
    // Determinar la acción a realizar basada en el parámetro 'accion' de la URL
    $accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

    if ($accion == 'crear') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar datos del formulario
            $telefono = $_POST['telefono'];
            $nif = $_POST['nif'];
            $nombre = $_POST['nombre'];
            $direccion = $_POST['direccion'];
            $poblacion = $_POST['poblacion'];
            $email = $_POST['email'];
            $metodo_pago = $_POST['metodo_pago'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("INSERT INTO cliente (telefono, nif, nombre, direccion, poblacion, email, metodo_pago) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $telefono, $nif, $nombre, $direccion, $poblacion, $email, $metodo_pago);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: clientes.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        ?>

        <h2>Agregar Cliente</h2>
        <form method="POST" action="clientes.php?accion=crear">
            <input type="text" name="telefono" placeholder="Teléfono">
            <input type="text" name="nif" placeholder="NIF" required>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="text" name="poblacion" placeholder="Población" required>
            <input type="email" name="email" placeholder="Email">
            <input type="text" name="metodo_pago" placeholder="Método de Pago">
            <button type="submit">Agregar</button>
        </form>
        <div class="volver-a" style="text-align: center; margin-top: 10px;">
            <a href="empleados.php" class="menu-item">Volver a Clientes</a>
        </div>

        <?php
    } elseif ($accion == 'editar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo && $_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar datos del formulario
            $telefono = $_POST['telefono'];
            $nif = $_POST['nif'];
            $nombre = $_POST['nombre'];
            $direccion = $_POST['direccion'];
            $poblacion = $_POST['poblacion'];
            $email = $_POST['email'];
            $metodo_pago = $_POST['metodo_pago'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("UPDATE cliente SET telefono=?, nif=?, nombre=?, direccion=?, poblacion=?, email=?, metodo_pago=? WHERE codigo=?");
            $stmt->bind_param("sssssssi", $telefono, $nif, $nombre, $direccion, $poblacion, $email, $metodo_pago, $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: clientes.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            // Mostrar formulario con datos existentes
            $stmt = $conexion->prepare("SELECT * FROM cliente WHERE codigo=?");
            $stmt->bind_param("i", $codigo);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $cliente = $resultado->fetch_assoc();
            ?>

            <h2>Editar Cliente</h2>
            <form method="POST" action="clientes.php?accion=editar&codigo=<?php echo $codigo; ?>">
                <input type="text" name="telefono" value="<?php echo $cliente['telefono']; ?>">
                <input type="text" name="nif" value="<?php echo $cliente['nif']; ?>" required>
                <input type="text" name="nombre" value="<?php echo $cliente['nombre']; ?>" required>
                <input type="text" name="direccion" value="<?php echo $cliente['direccion']; ?>" required>
                <input type="text" name="poblacion" value="<?php echo $cliente['poblacion']; ?>" required>
                <input type="email" name="email" value="<?php echo $cliente['email']; ?>">
                <input type="text" name="metodo_pago" value="<?php echo $cliente['metodo_pago']; ?>">
                <button type="submit">Actualizar</button>
            </form>

            <?php
        }
    } elseif ($accion == 'eliminar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo) {
            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("DELETE FROM cliente WHERE codigo=?");
            $stmt->bind_param("i", $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: clientes.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        // Listar clientes (acción predeterminada)
        $sql = "SELECT * FROM cliente";
        $resultado = mysqli_query($conexion, $sql);

        echo "<h1>Lista de Clientes</h1>";
        echo "<a href='clientes.php?accion=crear' class='menu-item'>Agregar Cliente</a>";
        echo "<table border='1'>";
        echo "<tr>
            <th>Código</th>
            <th>Teléfono</th>
            <th>NIF</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Población</th>
            <th>Email</th>
            <th>Método de Pago</th>
            <th>Acciones</th>
        </tr>";

        // Iterar sobre los resultados y mostrarlos en la tabla
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "<tr>
                <td>{$fila['codigo']}</td>
                <td>{$fila['telefono']}</td>
                <td>{$fila['nif']}</td>
                <td>{$fila['nombre']}</td>
                <td>{$fila['direccion']}</td>
                <td>{$fila['poblacion']}</td>
                <td>{$fila['email']}</td>
                <td>{$fila['metodo_pago']}</td>
                <td>
                    <a href='clientes.php?accion=editar&codigo={$fila['codigo']}'>Editar</a> |
                    <a href='clientes.php?accion=eliminar&codigo={$fila['codigo']}' 
                        onclick='return confirm(\"¿Estás seguro de eliminar este cliente?\");'>Eliminar</a>
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








