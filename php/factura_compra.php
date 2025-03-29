<?php
include '..\config\conexion_be.php'; // Incluye la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturas de Compras - ERP</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/estilos_menu.css">
    <script>
        function verificarProveedoresYEmpleados() {
            return verificarProveedores() && verificarEmpleados();
        }
        function verificarProveedores() {
            var proveedoresCount = <?php
            $proveedores = $conexion->query("SELECT COUNT(*) as total FROM proveedor");
            echo $proveedores->fetch_assoc()['total'];
            ?>;
            if (proveedoresCount == 0) {
                if (confirm("No hay proveedores registrados. Por favor, registre al menos un proveedor antes de agregar facturas. ¿Desea ir a la página de proveedores?")) {
                    window.location.href = 'proveedores.php?accion=crear';
                }
                return false;
            }
            return true;
        }
        function verificarEmpleados() {
            var empleadosCount = <?php
            $empleados = $conexion->query("SELECT COUNT(*) as total FROM empleados");
            echo $empleados->fetch_assoc()['total'];
            ?>;
            if (empleadosCount == 0) {
                if (confirm("No hay empleados registrados. Por favor, registre al menos un empleado antes de agregar facturas. ¿Desea ir a la página de empleados?")) {
                    window.location.href = 'empleados.php?accion=crear';
                }
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <a href="../index.html" class="logout-btn">Cerrar Sesión</a>
    <div class="container">
        <!-- Menu de botones -->
        <div class="menu-container">
            <a href="clientes.php" class="menu-item">Clientes</a>
            <a href="proveedores.php" class="menu-item">Proveedores</a>
            <a href="empleados.php" class="menu-item">Empleados</a>
            <a href="productos.php" class="menu-item" onclick="return verificarProveedores()&& verificarAlmacenes();">Productos</a>
            <a href="almacenes.php" class="menu-item">Almacenes</a>
            <a href="factura_compra.php" class="menu-item-active" onclick="return verificarProveedores()&& verificarAlmacenes()&& verificarProductos();">Factura de Compra</a>
            <a href="factura_venta.php" class="menu-item">Factura de Venta</a>
        </div>

        <div class="content-container">

    <?php
    // Determinar la acción a realizar basada en el parámetro 'accion' de la URL
    $accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

    if ($accion == 'crear') {
        // Obtener los códigos de los proveedores y empleados existentes
        $proveedores = $conexion->query("SELECT codigo, nombre FROM proveedor");
        $empleados = $conexion->query("SELECT codigo, nombre FROM empleados");

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar datos del formulario
            $fecha = $_POST['fecha'];
            $direccion = $_POST['direccion'];
            $codigo_proveedor = $_POST['codigo_proveedor'];
            $codigo_empleado = $_POST['codigo_empleado'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("INSERT INTO factura_compra (fecha, direccion, codigo_proveedor, codigo_empleado) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssii", $fecha, $direccion, $codigo_proveedor, $codigo_empleado);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: factura_compra.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        ?>
        <h2>Agregar Factura de Compra</h2>
        <form method="POST" action="factura_compra.php?accion=crear">
            <input type="date" name="fecha" placeholder="Fecha" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <select name="codigo_proveedor" required>
                <option value="">Seleccione un proveedor</option>
                <?php while ($proveedor = $proveedores->fetch_assoc()) { ?>
                    <option value="<?php echo $proveedor['codigo']; ?>">
                        <?php echo $proveedor['codigo'] . " - " . $proveedor['nombre']; ?></option>
                <?php } ?>
            </select>
            <select name="codigo_empleado" required>
                <option value="">Seleccione un empleado</option>
                <?php while ($empleado = $empleados->fetch_assoc()) { ?>
                    <option value="<?php echo $empleado['codigo']; ?>">
                        <?php echo $empleado['codigo'] . " - " . $empleado['nombre']; ?></option>
                <?php } ?>
            </select>
            <button type="submit">Agregar</button>
        </form>
        <div class="volver-a" style="text-align: center; margin-top: 10px;">
            <a href="factura_compra.php" class="menu-item">Volver a Facturas</a>
        </div>

    <?php } elseif ($accion == 'editar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo && $_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar datos del formulario
            $fecha = $_POST['fecha'];
            $direccion = $_POST['direccion'];
            $codigo_proveedor = $_POST['codigo_proveedor'];
            $codigo_empleado = $_POST['codigo_empleado'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("UPDATE factura_compra SET fecha = ?, direccion = ?, codigo_proveedor = ?, codigo_empleado = ? WHERE codigo = ?");
            $stmt->bind_param("ssiii", $fecha, $direccion, $codigo_proveedor, $codigo_empleado, $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: factura_compra.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        // Obtener los datos de la factura a editar
        $stmt = $conexion->prepare("SELECT * FROM factura_compra WHERE codigo = ?");
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        $factura = $result->fetch_assoc();

        // Obtener los códigos de los proveedores y empleados existentes
        $proveedores = $conexion->query("SELECT codigo, nombre FROM proveedor");
        $empleados = $conexion->query("SELECT codigo, nombre FROM empleados");
        ?>

        <h2>Editar Factura de Compra</h2>
        <form method="POST" action="factura_compra.php?accion=editar&codigo=<?php echo $codigo; ?>">
            <input type="date" name="fecha" placeholder="Fecha" value="<?php echo $factura['fecha']; ?>" required>
            <input type="text" name="direccion" placeholder="Dirección" value="<?php echo $factura['direccion']; ?>" required>
            <select name="codigo_proveedor" required>
                <option value="">Seleccione un proveedor</option>
                <?php while ($proveedor = $proveedores->fetch_assoc()) { ?>
                    <option value="<?php echo $proveedor['codigo']; ?>" <?php if ($proveedor['codigo'] == $factura['codigo_proveedor']) echo 'selected'; ?>>
                        <?php echo $proveedor['codigo'] . " - " . $proveedor['nombre']; ?>
                    </option>
                <?php } ?>
            </select>
            <select name="codigo_empleado" required>
                <option value="">Seleccione un empleado</option>
                <?php while ($empleado = $empleados->fetch_assoc()) { ?>
                    <option value="<?php echo $empleado['codigo']; ?>" <?php if ($empleado['codigo'] == $factura['codigo_empleado']) echo 'selected'; ?>>
                        <?php echo $empleado['codigo'] . " - " . $empleado['nombre']; ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit">Actualizar</button>
        </form>
        <div class="volver-a" style="text-align: center; margin-top: 10px;">
            <a href="factura_compra.php" class="menu-item">Volver a Facturas</a>
        </div>

    <?php } elseif ($accion == 'eliminar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo) {
            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("DELETE FROM factura_compra WHERE codigo = ?");
            $stmt->bind_param("i", $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: factura_compra.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        // Mostrar listado de facturas de compras
        $sql = "SELECT * FROM factura_compra";
        $resultado = mysqli_query($conexion, $sql);
        ?>

        <h1>Lista de Facturas de Compras</h1>
        <a href="factura_compra.php?accion=crear" class="btn_agregar_item" onclick="return verificarProveedoresYEmpleados()">+</a>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha</th>
                    <th>Dirección</th>
                    <th>Código del Proveedor</th>
                    <th>Código del Empleado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($factura = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $factura['codigo'] . "</td>";
                    echo "<td>" . $factura['fecha'] . "</td>";
                    echo "<td>" . $factura['direccion'] . "</td>";
                    echo "<td>" . $factura['codigo_proveedor'] . "</td>";
                    echo "<td>" . $factura['codigo_empleado'] . "</td>";
                    echo "<td>";
                    echo "<a href='factura_compra.php?accion=editar&codigo=" . $factura['codigo'] . "'>Editar</a>";
                    echo " | ";
                    echo "<a href='factura_compra.php?accion=eliminar&codigo=" . $factura['codigo'] . "' onclick='return confirm(\"¿Estás seguro de eliminar esta factura?\");'>Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
    }