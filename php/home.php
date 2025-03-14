<?php
session_start(); // Iniciar sesión
include('conexion_be.php'); // Incluye la conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    echo '<script>
        alert("Debes iniciar sesión primero.");
        window.location="../index.php";
    </script>';
    exit();
}

$nombre_completo = $_SESSION['nombre_completo']; // Obtener el nombre completo


// Verificar si hay proveedores registrados
$proveedores = $conexion->query("SELECT COUNT(*) as total FROM proveedor");
$proveedores_count = $proveedores->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal - ERP</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/estilos_menu.css">
    <script>
        function verificarProveedores() {
            var proveedoresCount = <?php echo $proveedores_count; ?>;
            if (proveedoresCount == 0) {
                if (confirm("No hay proveedores registrados. Por favor, registre al menos un proveedor antes de agregar productos. ¿Desea ir a la página de proveedores?")) {
                    window.location.href = 'proveedores.php?accion=crear';
                }
                return false;
            }
            return true;
        }
        function verificarAlmacenes() {
            var almacenesCount = <?php
                $almacenes = $conexion->query("SELECT COUNT(*) as total FROM almacen");
                echo $almacenes->fetch_assoc()['total'];
            ?>;
            if (almacenesCount == 0) {
                if (confirm("No hay almacenes registrados. Por favor, registre al menos un almacén antes de agregar productos. ¿Desea ir a la página de almacenes?")) {
                    window.location.href = 'almacenes.php?accion=crear';
                }
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <a href="../index.php" class="logout-btn">Cerrar Sesión</a>

    <h1>Bienvenido, <?php echo htmlspecialchars($nombre_completo); ?> </h1>
    <div class="menu-container">
        <a href="clientes.php" class="menu-item">Clientes</a>
        <a href="proveedores.php" class="menu-item">Proveedores</a>
        <a href="empleados.php" class="menu-item">Empleados</a>
        <a href="productos.php" class="menu-item" onclick="return verificarProveedores();">Productos</a>
        <a href="almacenes.php" class="menu-item" onclick="return verificarAlmacenes();">Almacenes</a>
        <a href="factura_compra.php" class="menu-item">Factura de Compra</a>
        <a href="factura_venta.php" class="menu-item">Factura de Venta</a>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> ERP System. Todos los derechos reservados.
    </footer>
</body>
</html>