<?php
// Inclusión de archivos necesarios:
require_once '../config/database.php';
require_once '../models/FacturaVenta.php';
require_once '../models/DetalleFacturaVenta.php';

$detalleModel = new DetalleFacturaVenta($db);

// Instancia del modelo FacturaVenta, pasando la conexión a la base de datos
$facturaVentaModel = new FacturaVenta($db);

// Lógica para GUARDAR UNA NUEVA FACTURA DE VENTA
// Se ejecuta cuando se envía el formulario de creación
// Recupera los datos del formulario enviados con el método POST

if (isset($_POST['action']) && $_POST['action'] == 'create') {
    error_log("Entrando en el bloque de creación de factura de venta");

    $fecha = $_POST['fecha'];
    $direccion = $_POST['direccion'];
    $codigo_cliente = $_POST['codigo_cliente'];
    $codigo_empleado = $_POST['codigo_empleado'];

    if ($facturaVentaModel->create($fecha, $direccion, $codigo_cliente, $codigo_empleado)) {
        $codigo_factura = $db->lastInsertId(); // obtener ID de la nueva factura

        // Insertar productos
        foreach ($_POST['productos'] as $index => $producto_id) {
            $cantidad = $_POST['cantidades'][$index];
            $detalleModel->insertarDetalle($codigo_factura, $producto_id, $cantidad);
        }

        header('Location: ../controllers/FacturaVentaController.php?action=list');
        exit();
    } else {
        echo "Error al crear la factura de venta.";
    }
}

// Lógica para ACTUALIZAR UNA FACTURA DE VENTA
// Este bloque se ejecuta cuando se envía el formulario de edición
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    error_log("Entrando en el bloque de edición de factura de venta", 0); // Log para depuración
    $codigo = $_POST['codigo'];
    $fecha = $_POST['fecha'];
    $direccion = $_POST['direccion'];
    $codigo_cliente = $_POST['codigo_cliente'];
    $codigo_empleado = $_POST['codigo_empleado'];

    // Con este if, se intenta actualizar una factura de venta.
    // Utiliza el método update() del modelo FacturaVenta.
    if ($facturaVentaModel->update($codigo, $fecha, $direccion, $codigo_cliente, $codigo_empleado)) {
        // Primero eliminamos los detalles existentes
        $detalleModel->eliminarDetallesPorFactura($codigo);
        // Luego insertamos los nuevos
        foreach ($_POST['productos'] as $index => $producto_id) {
            $cantidad = $_POST['cantidades'][$index];
            $detalleModel->insertarDetalle($codigo, $producto_id, $cantidad);
        }
        header('Location: ../controllers/FacturaVentaController.php?action=list');
        exit();
    } else {
        echo "Error al actualizar la factura de venta.";
    }
}

// Lógica para ELIMINAR UNA FACTURA
// Se ejecuta cuando se hace clic en el botón de eliminar
elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Con este if, se intenta eliminar una factura.
    // Utiliza el método delete() del modelo FacturaVenta.
    // Si se consigue, redirige de nuevo a la lista de facturas de venta
    if ($facturaVentaModel->delete($codigo)) {
        header('Location: ../controllers/FacturaVentaController.php?action=list'); // Redirigir a la lista
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al eliminar la factura de venta.";
    }
}

// Lógica para LISTAR FACTURAS DE VENTA
// Se ejecuta cuando se accede a la página
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $facturas = $facturaVentaModel->selectAll();
    include '../views/layouts/header.php';
    include '../views/facturaVenta/index.php';
    include '../views/layouts/footer.php';
}

// Lógica para mostrar el FORMULARIO DE CREAR FACTURA DE VENTA
// Se ejecuta cuando se hace clic en el botón de agregar factura
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    
    // Necesitamos los clientes, empleados y productos para llenar los selects en el formulario de creación
    // Clientes
    require_once '../models/Cliente.php';
    $clienteModel = new Cliente($db);
    $clientes = $clienteModel->selectAll();

    // Empleados
    require_once '../models/Empleado.php';
    $empleadoModel = new Empleado($db);
    $empleados = $empleadoModel->selectAll();

    // Productos
    require_once '../models/Producto.php';
    $productoModel = new Producto($db);
    $productos = $productoModel->selectAll();

    include '../views/layouts/header.php';
    include '../views/facturaVenta/crear.php';
    include '../views/layouts/footer.php';
}

// Lógica para mostrar el FORMULARIO DE EDITAR FACTURA DE VENTA
// Se ejecuta cuando se hace clic en el botón de editar
elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $factura = $facturaVentaModel->getById($codigo);
    $productos_factura = $detalleModel->obtenerDetallesPorFactura($codigo);

    // Necesitamos los clientes, empleados y productos para llenar los selects en el formulario de edición
    // Clientes
    require_once '../models/Cliente.php';
    $clienteModel = new Cliente($db);
    $clientes = $clienteModel->selectAll();

    // Empleados
    require_once '../models/Empleado.php';
    $empleadoModel = new Empleado($db);
    $empleados = $empleadoModel->selectAll();

    // Productos
    require_once '../models/Producto.php';
    $productoModel = new Producto($db);
    $productos = $productoModel->selectAll();

    include '../views/layouts/header.php';
    include '../views/facturaVenta/editar.php';
    include '../views/layouts/footer.php';
}
