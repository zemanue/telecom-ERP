<?php
include '..\config\conexion_be.php'; // Incluye la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturas de Ventas - ERP</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/estilos_menu.css">
    <script>
        function verificarClientesYEmpleados() {
            return verificarClientes() && verificarEmpleados();
        }
        function verificarClientes() {
            var clientesCount = <?php
            $clientes = $conexion->query("SELECT COUNT(*) as total FROM cliente");
            echo $clientes->fetch_assoc()['total'];
            ?>;
            if (clientesCount == 0) {
                if (confirm("No hay clientes registrados. Por favor, registre al menos un cliente antes de agregar facturas. ¿Desea ir a la página de clientes?")) {
                    window.location.href = 'clientes.php?accion=crear';
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
            <a href="productos.php" class="menu-item" onclick="return verificarClientes()&& verificarAlmacenes();">Productos</a>
            <a href="almacenes.php" class="menu-item">Almacenes</a>
            <a href="factura_compra.php" class="menu-item" onclick="return verificarProveedores()&& verificarAlmacenes()&& verificarProductos();">Factura de Compra</a>
            <a href="factura_venta.php" class="menu-item-active" onclick="return verificarClientes()&& verificarAlmacenes()&& verificarProductos();">Factura de Venta</a>
        </div>

        <div class="content-container">

    <?php
    // Determinar la acción a realizar basada en el parámetro 'accion' de la URL
    $accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

    if ($accion == 'crear') {
        // Obtener los códigos de los clientes y empleados existentes
        $clientes = $conexion->query("SELECT codigo, nombre FROM cliente");
        $empleados = $conexion->query("SELECT codigo, nombre FROM empleados");

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar datos del formulario
            $fecha = $_POST['fecha'];
            $direccion = $_POST['direccion'];
            $codigo_cliente = $_POST['codigo_cliente'];
            $codigo_empleado = $_POST['codigo_empleado'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("INSERT INTO facturas_venta (fecha, direccion, codigo_cliente, codigo_empleado) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssii", $fecha, $direccion, $codigo_cliente, $codigo_empleado);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: factura_venta.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        ?>
        <h2>Agregar Factura de Venta</h2>
        <form method="POST" action="factura_venta.php?accion=crear">
            <input type="date" name="fecha" placeholder="Fecha" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <select name="codigo_cliente" required>
                <option value="">Seleccione un cliente</option>
                <?php while ($cliente = $clientes->fetch_assoc()) { ?>
                    <option value="<?php echo $cliente['codigo']; ?>">
                        <?php echo $cliente['codigo'] . " - " . $cliente['nombre']; ?></option>
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
            <a href="factura_venta.php" class="menu-item">Volver a Facturas</a>
        </div>

    <?php } elseif ($accion == 'editar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo && $_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar datos del formulario
            $fecha = $_POST['fecha'];
            $direccion = $_POST['direccion'];
            $codigo_cliente = $_POST['codigo_cliente'];
            $codigo_empleado = $_POST['codigo_empleado'];

            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("UPDATE facturas_venta SET fecha = ?, direccion = ?, codigo_cliente = ?, codigo_empleado = ? WHERE codigo = ?");
            $stmt->bind_param("ssiii", $fecha, $direccion, $codigo_cliente, $codigo_empleado, $codigo);

            // Ejecutar la consulta y verificar si fue exitosa
            if ($stmt->execute()) {
                header("Location: factura_venta.php"); // Redirigir al listado
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        // Obtener los datos de la factura a editar
        $stmt = $conexion->prepare("SELECT * FROM facturas_venta WHERE codigo = ?");
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        $factura = $result->fetch_assoc();

        // Obtener los códigos de los clientes y empleados existentes
        $clientes = $conexion->query("SELECT codigo, nombre FROM cliente");
        $empleados = $conexion->query("SELECT codigo, nombre FROM empleados");
        ?>

        <h2>Editar Factura de Venta</h2>
        <form method="POST" action="factura_venta.php?accion=editar&codigo=<?php echo $codigo; ?>">
            <input type="date" name="fecha" placeholder="Fecha" value="<?php echo $factura['fecha']; ?>" required>
            <input type="text" name="direccion" placeholder="Dirección" value="<?php echo $factura['direccion']; ?>" required>
            <select name="codigo_cliente" required>
                <option value="">Seleccione un cliente</option>
                <?php while ($cliente = $clientes->fetch_assoc()) { ?>
                    <option value="<?php echo $cliente['codigo']; ?>" <?php if ($cliente['codigo'] == $factura['codigo_cliente']) echo 'selected'; ?>>
                        <?php echo $cliente['codigo'] . " - " . $cliente['nombre']; ?>
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
            <a href="factura_venta.php" class="menu-item">Volver a Facturas</a>
        </div>

    <?php } elseif ($accion == 'eliminar') {
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        if ($codigo) {
            // Usar prepared statements para prevenir SQL injection
            $stmt = $conexion->prepare("DELETE FROM facturas_venta WHERE codigo = ?");
            $stmt->bind_param("i", $codigo);

                        // Ejecutar la consulta y verificar si fue exitosa
                        if ($stmt->execute()) {
                            header("Location: factura_venta.php"); // Redirigir al listado
                            exit();
                        } else {
                            echo "Error: " . $stmt->error;
                        }
                    }
                } else {
                    // Mostrar listado de facturas de ventas
                    $sql = "SELECT * FROM facturas_venta";
                    $resultado = mysqli_query($conexion, $sql);
                    ?>
            
                    <h1>Lista de Facturas de Ventas</h1>
                    <a href="factura_venta.php?accion=crear" class="btn_agregar_item" onclick="return verificarClientesYEmpleados()">+</a>
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Fecha</th>
                                <th>Dirección</th>
                                <th>Código del Cliente</th>
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
                                echo "<td>" . $factura['codigo_cliente'] . "</td>";
                                echo "<td>" . $factura['codigo_empleado'] . "</td>";
                                echo "<td>";
                                echo "<a href='factura_venta.php?accion=editar&codigo=" . $factura['codigo'] . "'>Editar</a>";
                                echo " | ";
                                echo "<a href='factura_venta.php?accion=eliminar&codigo=" . $factura['codigo'] . "' onclick='return confirm(\"¿Estás seguro de eliminar esta factura?\");'>Eliminar</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
            
                    <?php
                }
                ?>
                    </div>
                </div>
            </body>
            </html>
            