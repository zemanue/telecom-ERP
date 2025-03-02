<?php
include('conexion_be.php'); // Incluye la conexión a la base de datos

// Incluir el archivo CSS externo
echo '<link rel="stylesheet" type="text/css" href="../assets/css/estilos_menu.css">';

// Mostrar botón para regresar al menú principal
echo '<a href="home.php">Volver al Menú</a>';

// Determinar la acción a realizar basada en el parámetro 'accion' de la URL
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

if ($accion == 'crear') {
    // Mostrar formulario para crear un cliente
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Procesar datos del formulario
        $telefono = $_POST['telefono'];
        $nif = $_POST['nif'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $poblacion = $_POST['poblacion'];
        $email = $_POST['email'];
        $metodo_pago = $_POST['metodo_pago'];

        $sql = "INSERT INTO cliente (telefono, nif, nombre, direccion, poblacion, email, metodo_pago) 
                VALUES ('$telefono', '$nif', '$nombre', '$direccion', '$poblacion', '$email', '$metodo_pago')";
        if (mysqli_query($conexion, $sql)) {
            header("Location: clientes.php"); // Redirigir al listado
            exit();
        } else {
            echo "Error: " . mysqli_error($conexion);
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
    <?php
} elseif ($accion == 'editar') {
    // Editar cliente existente
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

        $sql = "UPDATE cliente SET telefono='$telefono', nif='$nif', nombre='$nombre', direccion='$direccion',
                poblacion='$poblacion', email='$email', metodo_pago='$metodo_pago' WHERE codigo=$codigo";
        if (mysqli_query($conexion, $sql)) {
            header("Location: clientes.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    } else {
        // Mostrar formulario con datos existentes
        $sql = "SELECT * FROM cliente WHERE codigo=$codigo";
        $resultado = mysqli_query($conexion, $sql);
        $cliente = mysqli_fetch_assoc($resultado);
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
    // Eliminar cliente
    $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
    if ($codigo) {
        $sql = "DELETE FROM cliente WHERE codigo=$codigo";
        if (mysqli_query($conexion, $sql)) {
            header("Location: clientes.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conexion);
        }
    }
} else {
    // Listar clientes (acción predeterminada)
    $sql = "SELECT * FROM cliente";
    $resultado = mysqli_query($conexion, $sql);

    echo "<h1>Lista de Clientes</h1>";
    echo "<a href='clientes.php?accion=crear'>Agregar Cliente</a>";
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
mysqli_close($conexion);
?>








