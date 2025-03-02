<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal - ERP</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/estilos_menu.css">
</head>
<body>
    <h1>Bienvenido al ERP</h1>
    <div class="menu-container">
        <a href="clientes.php" class="menu-item">Clientes</a>
        <a href="proveedores.php" class="menu-item">Proveedores</a>
        <a href="empleados.php" class="menu-item">Empleados</a>
        <a href="productos.php" class="menu-item">Productos</a>
        <a href="factura_compra.php" class="menu-item">Factura de Compra</a>
        <a href="factura_venta.php" class="menu-item">Factura de Venta</a>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> ERP System. Todos los derechos reservados.
    </footer>
</body>
</html>