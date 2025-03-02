<?php
include('conexion_be.php'); // Incluye la conexión a la base de datos

// Incluir el archivo CSS externo
echo '<link rel="stylesheet" type="text/css" href="../assets/css/estilos_menu.css">';

// Mostrar botón para regresar al menú principal
echo '<a href="home.php">Volver al Menú</a>';

// Determinar la acción a realizar basada en el parámetro 'accion' de la URL
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

if ($accion == 'crear') {
    // Mostrar formulario para crear un proveedor
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Procesar datos del formulario
        $telefono = $_POST['telefono'];
        $nif = $_POST['nif'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $poblacion = $_POST['poblacion'];
        $email = $_POST['email'];
        $deuda_existente = $_POST['deuda_existente'];

        $sql = "INSERT INTO proveedor (telefono, nif, nombre, direccion, poblacion, email, deuda_existente) 
                VALUES ('$telefono', '$nif', '$nombre', '$direccion', '$poblacion', '$email', '$deuda_existente')";
        if (mysqli_query($conexion, $sql)) {
            header("Location: proveedores.php"); // Redirigir al listado
            exit();
        } else {
            echo "Error: " . mysqli_error($conexion);
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
    // Editar proveedor existente
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

        $sql = "UPDATE proveedor SET telefono='$telefono', nif='$nif', nombre='$nombre', direccion='$direccion',
                poblacion='$poblacion', email='$email', deuda_existente='$deuda_existente' WHERE codigo=$codigo";
        if (mysqli_query($conexion, $sql)) {
            header("Location: proveedores.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    } else {
        // Mostrar formulario con datos existentes
        $sql = "SELECT * FROM proveedor WHERE codigo=$codigo";
        $resultado = mysqli_query($conexion, $sql);
        $proveedor = mysqli_fetch_assoc($resultado);
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
    // Eliminar proveedor
    $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
    if ($codigo) {
        $sql = "DELETE FROM proveedor WHERE codigo=$codigo";
        if (mysqli_query($conexion, $sql)) {
            header("Location: proveedores.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    }
} else {
    // Listar proveedores (acción predeterminada)
    $sql = "SELECT * FROM proveedor";
    $resultado = mysqli_query($conexion, $sql);

    echo "<h1>Lista de Proveedores</h1>";
    echo "<a href='proveedores.php?accion=crear' style='margin: 10px 0; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;'>Agregar Proveedor</a>";
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
mysqli_close($conexion);
?>
