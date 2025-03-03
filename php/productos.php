<?php
include('conexion_be.php'); // Incluye la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - ERP</title>
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
            $nombre = $_POST['nombre'];
            $precio_compra = $_POST['precio_compra'];
            $precio_venta = $_POST['precio_venta'];
            $iva = $_POST['IVA'];
            $codigo_proveedor = $_POST['codigo_proveedor'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("INSERT INTO productos (nombre, precio_compra, precio_venta, IVA, codigo_proveedor) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sddii", $nombre, $precio_compra, $precio_venta, $iva, $codigo_proveedor);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: productos.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        // Obtener los códigos de los proveedores existentes
        $proveedores = $conexion->query("SELECT codigo, nombre FROM proveedor");
        ?>

        <h2>Agregar Producto</h2>
        <form method="POST" action="productos.php?accion=crear">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="number" step="0.01" name="precio_compra" placeholder="Precio de Compra" required>
            <input type="number" step="0.01" name="precio_venta" placeholder="Precio de Venta" required>
            <input type="number" step="0.01" name="IVA" placeholder="IVA" required>
            <select name="codigo_proveedor" required>
                <option value="">Seleccione un proveedor</option>
                <?php while ($proveedor = $proveedores->fetch_assoc()) { ?>
                    <option value="<?php echo $proveedor['codigo']; ?>"><?php echo $proveedor['codigo'] . " - " . $proveedor['nombre']; ?></option>
                <?php } ?>
            </select>
            <button type="submit">Agregar</button>
        </form>
        <div class="volver-a" style="text-align: center; margin-top: 10px;">
            <a href="productos.php" class="menu-item">Volver a Productos</a>
        </div>

        <?php
    } elseif ($accion == 'editar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo && $_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar datos del formulario
            $nombre = $_POST['nombre'];
            $precio_compra = $_POST['precio_compra'];
            $precio_venta = $_POST['precio_venta'];
            $iva = $_POST['IVA'];
            $codigo_proveedor = $_POST['codigo_proveedor'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("UPDATE productos SET nombre = ?, precio_compra = ?, precio_venta = ?, IVA = ?, codigo_proveedor = ? WHERE codigo = ?");
            $stmt->bind_param("sddiii", $nombre, $precio_compra, $precio_venta, $iva, $codigo_proveedor, $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: productos.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        // Obtener los datos del producto a editar
        $stmt = $conexion->prepare("SELECT * FROM productos WHERE codigo = ?");
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();

        // Obtener los códigos de los proveedores existentes
        $proveedores = $conexion->query("SELECT codigo, nombre FROM proveedor");
        ?>

        <h2>Editar Producto</h2>
        <form method="POST" action="productos.php?accion=editar&codigo=<?php echo $codigo; ?>">
            <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $producto['nombre']; ?>" required>
            <input type="number" step="0.01" name="precio_compra" placeholder="Precio de Compra" value="<?php echo $producto['precio_compra']; ?>" required>
            <input type="number" step="0.01" name="precio_venta" placeholder="Precio de Venta" value="<?php echo $producto['precio_venta']; ?>" required>
            <input type="number" step="0.01" name="IVA" placeholder="IVA" value="<?php echo $producto['IVA']; ?>" required>
            <select name="codigo_proveedor" required>
                <option value="">Seleccione un proveedor</option>
                <?php while ($proveedor = $proveedores->fetch_assoc()) { ?>
                    <option value="<?php echo $proveedor['codigo']; ?>" <?php if ($proveedor['codigo'] == $producto['codigo_proveedor']) echo 'selected'; ?>>
                        <?php echo $proveedor['codigo'] . " - " . $proveedor['nombre']; ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit">Actualizar</button>
        </form>

        <?php
    } elseif ($accion == 'eliminar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo) {
            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("DELETE FROM productos WHERE codigo = ?");
            $stmt->bind_param("i", $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: productos.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        // Mostrar listado de productos
        $sql = "SELECT * FROM productos";
        $resultado = mysqli_query($conexion, $sql);
        ?>

        <h1>Lista de Productos</h1>
        <a href="productos.php?accion=crear" class="menu-item">Agregar Producto</a>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Precio de Compra</th>
                    <th>Precio de Venta</th>
                    <th>IVA</th>
                    <th>Código de Proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($producto = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $producto['codigo'] . "</td>";
                    echo "<td>" . $producto['nombre'] . "</td>";
                    echo "<td>" . $producto['precio_compra'] . "</td>";
                    echo "<td>" . $producto['precio_venta'] . "</td>";
                    echo "<td>" . $producto['IVA'] . "</td>";
                    echo "<td>" . $producto['codigo_proveedor'] . "</td>";
                    echo "<td>";
                    echo "<a href='productos.php?accion=editar&codigo=" . $producto['codigo'] . "'>Editar</a>";
                    echo " | ";
                    echo "<a href='productos.php?accion=eliminar&codigo=" . $producto['codigo'] . "'>Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
    }
    ?>
</body>
</html>