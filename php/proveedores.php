<?php
include('conexion_be.php'); // Incluye la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores - ERP</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/estilos_menu.css">
</head>
<body>
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
            $deuda_existente = $_POST['deuda_existente'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("INSERT INTO proveedor (telefono, nif, nombre, direccion, poblacion, email, deuda_existente) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $telefono, $nif, $nombre, $direccion, $poblacion, $email, $deuda_existente);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: proveedores.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        ?>

        <h2>Agregar Proveedor</h2>
        <form method="POST" action="proveedores.php?accion=crear">
            <input type="text" name="telefono" placeholder="Teléfono">
            <input type="text" name="nif" placeholder="NIF" required>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="text" name="poblacion" placeholder="Población" required>
            <input type="email" name="email" placeholder="Email">
            <input type="number" step="0.01" name="deuda_existente" placeholder="Deuda Existente" value="0.00">
            <button type="submit">Agregar</button>
        </form>

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
            $deuda_existente = $_POST['deuda_existente'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("UPDATE proveedor SET telefono=?, nif=?, nombre=?, direccion=?, poblacion=?, email=?, deuda_existente=? WHERE codigo=?");
            $stmt->bind_param("sssssssi", $telefono, $nif, $nombre, $direccion, $poblacion, $email, $deuda_existente, $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: proveedores.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            // Mostrar formulario con datos existentes
            $stmt = $conexion->prepare("SELECT * FROM proveedor WHERE codigo=?");
            $stmt->bind_param("i", $codigo);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $proveedor = $resultado->fetch_assoc();
            ?>

            <h2>Editar Proveedor</h2>
            <form method="POST" action="proveedores.php?accion=editar&codigo=<?php echo $codigo; ?>">
                <input type="text" name="telefono" value="<?php echo $proveedor['telefono']; ?>">
                <input type="text" name="nif" value="<?php echo $proveedor['nif']; ?>" required>
                <input type="text" name="nombre" value="<?php echo $proveedor['nombre']; ?>" required>
                <input type="text" name="direccion" value="<?php echo $proveedor['direccion']; ?>" required>
                <input type="text" name="poblacion" value="<?php echo $proveedor['poblacion']; ?>" required>
                <input type="email" name="email" value="<?php echo $proveedor['email']; ?>">
                <input type="number" step="0.01" name="deuda_existente" value="<?php echo $proveedor['deuda_existente']; ?>">
                <button type="submit">Actualizar</button>
            </form>

            <?php
        }
    } elseif ($accion == 'eliminar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo) {
            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("DELETE FROM proveedor WHERE codigo=?");
            $stmt->bind_param("i", $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: proveedores.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        // Listar proveedores (acción predeterminada)
        $sql = "SELECT * FROM proveedor";
        $resultado = mysqli_query($conexion, $sql);

        echo "<h1>Lista de Proveedores</h1>";
        echo "<a href='proveedores.php?accion=crear' class='menu-item'>Agregar Proveedor</a>";
        echo "<table border='1'>";
        echo "<tr>
            <th>Código</th>
            <th>Teléfono</th>
            <th>NIF</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Población</th>
            <th>Email</th>
            <th>Deuda Existente</th>
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
                <td>{$fila['deuda_existente']}</td>
                <td>
                    <a href='proveedores.php?accion=editar&codigo={$fila['codigo']}'>Editar</a> |
                    <a href='proveedores.php?accion=eliminar&codigo={$fila['codigo']}' 
                       onclick='return confirm(\"¿Estás seguro de eliminar este proveedor?\");'>Eliminar</a>
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
