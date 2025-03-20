<?php
session_start(); // Iniciar sesión
include '../models/conexion_be.php'; // Incluye la conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    echo '<script>
        alert("Debes iniciar sesión primero.");
        window.location="../index.html";
    </script>';
    exit();
}

$nombre_completo = $_SESSION['nombre_completo']; // Obtener el nombre completo
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal - ERP</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/estilos_menu.css">
    <script defer src="../assets/js/verificaciones.js"></script>
</head>

<body>

    <a href="../index.html" class="logout-btn">Cerrar Sesión</a>
    <h1>Bienvenido, <?php echo htmlspecialchars($nombre_completo); ?></h1>

    <div class="menu-container">
        <a href="clientes.php" class="menu-item">Clientes</a>
        <a href="proveedores.php" class="menu-item">Proveedores</a>
        <a href="empleados.php" class="menu-item">Empleados</a>
        <a href="#" class="menu-item" onclick="return verificarProveedores();">Productos</a>
        <a href="#" class="menu-item" onclick="return verificarAlmacenes();">Almacenes</a>
        <a href="factura_compra.php" class="menu-item">Factura de Compra</a>
        <a href="factura_venta.php" class="menu-item">Factura de Venta</a>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> ERP System. Todos los derechos reservados.
    </footer>

</body>

</html>