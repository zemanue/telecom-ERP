<?php
// Inclusión de archivos necesarios:
require_once '../config/database.php';
require_once '../models/FacturaVenta.php';
require_once '../models/DetalleFacturaVenta.php';
require_once '../models/Producto.php'; // Asegúrate de incluir el modelo de Producto para reducir el stock

// Instancia de los modelos necesarios, pasando la conexión a la base de datos
$facturaVentaModel = new FacturaVenta($db);
$detalleModel = new DetalleFacturaVenta($db);
$productoModel = new Producto($db);

// Lógica para GUARDAR UNA NUEVA FACTURA DE VENTA
// Se ejecuta cuando se envía el formulario de creación
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    error_log("Entrando en el bloque de creación de factura de venta");

    $fecha = $_POST['fecha'];
    $direccion = $_POST['direccion'];
    $codigo_cliente = $_POST['codigo_cliente'];
    $codigo_empleado = $_POST['codigo_empleado'];

    // Preparamos los detalles de los productos (producto_id y cantidad)
    $detalles = [];
    foreach ($_POST['productos'] as $index => $producto_id) {
        $detalles[] = [
            'producto_id' => $producto_id,
            'cantidad' => $_POST['cantidades'][$index]
        ];
    }

    // Crear la factura de venta (esto también inserta los detalles y reduce el stock)
    if ($facturaVentaModel->create($fecha, $direccion, $codigo_cliente, $codigo_empleado, $detalles)) {
        // Redirige a la lista de facturas si todo fue exitoso
        header('Location: ../controllers/FacturaVentaController.php?action=list');
        exit();
    } else {
        // Si hubo un error, se muestra el mensaje
        echo "Error: La factura no se pudo crear correctamente.";
    }
}

// Lógica para ACTUALIZAR UNA FACTURA DE VENTA
// Este bloque se ejecuta cuando se envía el formulario de edición
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    error_log("Entrando en el bloque de edición de factura de venta", 0);

    $codigo = $_POST['codigo'];
    $fecha = $_POST['fecha'];
    $direccion = $_POST['direccion'];
    $codigo_cliente = $_POST['codigo_cliente'];
    $codigo_empleado = $_POST['codigo_empleado'];

    // Actualizamos la factura en la tabla principal
    if ($facturaVentaModel->update($codigo, $fecha, $direccion, $codigo_cliente, $codigo_empleado)) {
        // Primero eliminamos los detalles existentes
        $detalleModel->eliminarDetallesPorFactura($codigo);

        // Insertamos los nuevos detalles y actualizamos stock
        foreach ($_POST['productos'] as $index => $producto_id) {
            $cantidad = $_POST['cantidades'][$index];

            // Reducir el stock del producto
            $productoModel->reducirStock($producto_id, $cantidad);

            // Insertar nuevo detalle
            $detalleModel->insertarDetalle($codigo, $producto_id, $cantidad);
        }

        // Redirigimos a la lista de facturas
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

    // Se intenta eliminar la factura con el método del modelo
    if ($facturaVentaModel->delete($codigo)) {
        header('Location: ../controllers/FacturaVentaController.php?action=list');
        exit();
    } else {
        echo "Error al eliminar la factura de venta.";
    }
}

// Lógica para LISTAR FACTURAS DE VENTA
// Se ejecuta cuando se accede a la página principal de facturas
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $facturas = $facturaVentaModel->selectAll();

    include '../views/layouts/header.php';
    include '../views/facturaVenta/index.php';
    include '../views/layouts/footer.php';
}

// Lógica para mostrar el FORMULARIO DE CREAR FACTURA DE VENTA
// Se ejecuta cuando se hace clic en el botón de agregar factura
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {

    // Cargamos los datos necesarios para los selects (clientes, empleados, productos)
    require_once '../models/Cliente.php';
    require_once '../models/Empleado.php';

    $clienteModel = new Cliente($db);
    $clientes = $clienteModel->selectAll();

    $empleadoModel = new Empleado($db);
    $empleados = $empleadoModel->selectAll();

    $productos = $productoModel->selectAll();

    include '../views/layouts/header.php';
    include '../views/facturaVenta/crear.php';
    include '../views/layouts/footer.php';
}

// Lógica para mostrar el FORMULARIO DE EDITAR FACTURA DE VENTA
// Se ejecuta cuando se hace clic en el botón de editar
elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Obtener los datos actuales de la factura y sus productos
    $factura = $facturaVentaModel->getById($codigo);
    $productos_factura = $detalleModel->obtenerDetallesPorFactura($codigo);

    // Cargamos los datos para los selects
    require_once '../models/Cliente.php';
    require_once '../models/Empleado.php';

    $clienteModel = new Cliente($db);
    $clientes = $clienteModel->selectAll();

    $empleadoModel = new Empleado($db);
    $empleados = $empleadoModel->selectAll();

    $productos = $productoModel->selectAll();

    include '../views/layouts/header.php';
    include '../views/facturaVenta/editar.php';
    include '../views/layouts/footer.php';
}
?>
