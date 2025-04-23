<!-- 
Este archivo maneja la lógica para la sección de almacenes.
Es el intermediario entre los archivos de la vista (views/almacenes) y el modelo (models/Almacen.php).
Reciben las peticiones del usuario, interactúan con los modelos para obtener o modificar datos, 
y luego seleccionan la vista que se debe mostrar al usuario.
    - La lógica para listar, crear, modificar y eliminar almacenes utilizará
    los métodos definidos en el modelo Almacen.php (selectAll(), create(), update() y delete()).
    - Este controlador es el responsable de que se añadan a todas las páginas el header y el footer comunes.
-->

<?php
// Inclusión de archivos necesarios:
require_once '../config/database.php';
require_once '../models/FacturaCompra.php';
require_once '../models/DetalleFacturaCompra.php';

$detalleModel = new DetalleFacturaCompra($db);

// Instancia del modelo Almacén, pasando la conexión a la base de datos
$facturaCompraModel = new FacturaCompra($db);

// Lógica para GUARDAR UN NUEVA FACTURA DE COMPRA
// Se ejecuta cuando se envía el formulario de creación
// Recupera los datos del formulario enviados con el método POST

if (isset($_POST['action']) && $_POST['action'] == 'create') {
    error_log("Entrando en el bloque de creación de factura de compra");

    $fecha = $_POST['fecha'];
    $direccion = $_POST['direccion'];
    $codigo_proveedor = $_POST['codigo_proveedor'];
    $codigo_empleado = $_POST['codigo_empleado'];

    if ($facturaCompraModel->create($fecha, $direccion, $codigo_proveedor, $codigo_empleado)) {
        $codigo_factura = $db->lastInsertId(); // obtener ID de la nueva factura

        // Insertar productos
        foreach ($_POST['productos'] as $index => $producto_id) {
            $cantidad = $_POST['cantidades'][$index];
            $detalleModel->insertarDetalle($codigo_factura, $producto_id, $cantidad);
        }

        header('Location: ../controllers/FacturaCompraController.php?action=list');
        exit();
    } else {
        echo "Error al crear la factura de compra.";
    }
}

// Lógica para ACTUALIZAR UN ALMACÉN
// Este bloque se ejecuta cuando se envía el formulario de edición
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    error_log("Entrando en el bloque de edición de almacén", 0); // Log para depuración
    $codigo = $_POST['codigo'];
    $fecha = $_POST['fecha'];
    $direccion = $_POST['direccion'];
    $codigo_proveedor = $_POST['codigo_proveedor'];
    $codigo_empleado = $_POST['codigo_empleado'];

    // Con este if, se intenta actualizar un almacén.
    // Utiliza el método update() del modelo Almacén.
    if ($facturaCompraModel->update($codigo, $fecha, $direccion, $codigo_proveedor, $codigo_empleado)) {
        $detalleModel->eliminarDetallesPorFactura($codigo);
        // Luego insertamos los nuevos
        foreach ($_POST['productos'] as $index => $producto_id) {
        $cantidad = $_POST['cantidades'][$index];
        $detalleModel->insertarDetalle($codigo, $producto_id, $cantidad);
        }
        header('Location: ../controllers/FacturaCompraController.php?action=list');
        exit();
    } else {
        echo "Error al actualizar la factura de compra.";
    }
}


// Lógica para ELIMINAR UN ALMACÉN
// Se ejecuta cuando se hace clic en el botón de eliminar
elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Con este if, se intenta eliminar un almacén.
    // Utiliza el método delete() del modelo Almacén.
    // Si se consigue, redirige de nuevo a la lista de almacenes
    if ($facturaCompraModel->delete($codigo)) {
        header('Location: ../controllers/FacturaCompraController.php?action=list'); // Redirigir a la lista
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al eliminar el almacén.";
    }
}


// Lógica para LISTAR ALMACÉNES
// Se ejecuta cuando se accede a la página
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $facturas = $facturaCompraModel->selectAll();
    include '../views/layouts/header.php';
    include '../views/facturaCompra/index.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE CREAR ALMACÉN
// Se ejecuta cuando se hace clic en el botón de crear almacén
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    include '../views/layouts/header.php';
    include '../views/facturaCompra/crear.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE EDITAR ALMACÉN
// Se ejecuta cuando se hace clic en el botón de editar
elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $factura = $facturaCompraModel->getById($codigo);
    $productos_factura = $detalleModel->obtenerDetallesPorFactura($codigo); 
    include '../views/layouts/header.php';
    include '../views/facturaCompra/editar.php';
    include '../views/layouts/footer.php';
}


?>