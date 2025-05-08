<!-- 
Este archivo maneja la lógica para la sección de facturas de compra.
Es el intermediario entre los archivos de la vista (views/facturaCompra) y los modelos (models/FacturaCompra.php y models/DetalleFacturaCompra.php).
Recibe las peticiones del usuario, interactúa con los modelos para obtener o modificar datos, 
y luego selecciona la vista que se debe mostrar al usuario.
    - La lógica para listar, crear, modificar y eliminar facturas de compra utiliza
    los métodos definidos en los modelos FacturaCompra.php y DetalleFacturaCompra.php (selectAll(), create(), update(), delete(), etc.).
    - Este controlador también gestiona la relación entre facturas y sus detalles (productos asociados).
    - Además, es responsable de incluir el header y el footer comunes en todas las páginas.
-->

<?php
// Inclusión de archivos necesarios:
require_once '../config/database.php';
require_once '../models/FacturaCompra.php';
require_once '../models/DetalleFacturaCompra.php';
require_once '../models/Producto.php'; // para actualizar el stock

$detalleModel = new DetalleFacturaCompra($db);
$facturaCompraModel = new FacturaCompra($db);
$productoModel = new Producto($db); // instanciamos el modelo Producto

// Lógica para GUARDAR UN NUEVA FACTURA DE COMPRA
// Se ejecuta cuando se envía el formulario de creación
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    error_log("Entrando en el bloque de creación de factura de compra");

    $fecha = $_POST['fecha'];
    $direccion = $_POST['direccion'];
    $codigo_proveedor = $_POST['codigo_proveedor'];
    $codigo_empleado = $_POST['codigo_empleado'];

    try {
        // Iniciar transacción
        $db->beginTransaction();

        // Crear factura
        if ($facturaCompraModel->create($fecha, $direccion, $codigo_proveedor, $codigo_empleado)) {
            $codigo_factura = $db->lastInsertId(); // obtener ID de la nueva factura

            // Insertar productos
            foreach ($_POST['productos'] as $index => $producto_id) {
                $cantidad = $_POST['cantidades'][$index];

                // Insertar detalle
                $detalleModel->insertarDetalle($codigo_factura, $producto_id, $cantidad);

                // Sumar stock
                $productoModel->aumentarStock($producto_id, $cantidad);
            }

            // Confirmar transacción
            $db->commit();

            header('Location: ../controllers/FacturaCompraController.php?action=list');
            exit();
        } else {
            $db->rollBack();
            echo "Error al crear la factura de compra.";
        }
    } catch (Exception $e) {
        $db->rollBack();
        echo "Error al procesar la factura: " . $e->getMessage();
    }
}

// Lógica para ACTUALIZAR UNA FACTURA DE COMPRA
// Este bloque se ejecuta cuando se envía el formulario de edición
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    error_log("Entrando en el bloque de edición de factura de compra", 0); // Log para depuración
    $codigo = $_POST['codigo'];
    $fecha = $_POST['fecha'];
    $direccion = $_POST['direccion'];
    $codigo_proveedor = $_POST['codigo_proveedor'];
    $codigo_empleado = $_POST['codigo_empleado'];

    try {
        $db->beginTransaction();

        // Con este if, se intenta actualizar una factura de compra.
        // Utiliza el método update() del modelo FacturaCompra.
        if ($facturaCompraModel->update($codigo, $fecha, $direccion, $codigo_proveedor, $codigo_empleado)) {
            // Eliminar los detalles existentes
            $detalleModel->eliminarDetallesPorFactura($codigo);

            // Insertar los nuevos detalles y actualizar stock
            foreach ($_POST['productos'] as $index => $producto_id) {
                $cantidad = $_POST['cantidades'][$index];
                $detalleModel->insertarDetalle($codigo, $producto_id, $cantidad);
                $productoModel->sumarStock($producto_id, $cantidad); // si se requiere
            }

            $db->commit();
            header('Location: ../controllers/FacturaCompraController.php?action=list');
            exit();
        } else {
            $db->rollBack();
            echo "Error al actualizar la factura de compra.";
        }
    } catch (Exception $e) {
        $db->rollBack();
        echo "Error al actualizar la factura: " . $e->getMessage();
    }
}

// Lógica para ELIMINAR UNA FACTURA
// Se ejecuta cuando se hace clic en el botón de eliminar
elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Con este if, se intenta eliminar una factura.
    // Utiliza el método delete() del modelo FacturaCompra.
    // Si se consigue, redirige de nuevo a la lista de facturas de compra
    if ($facturaCompraModel->delete($codigo)) {
        header('Location: ../controllers/FacturaCompraController.php?action=list'); // Redirigir a la lista
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al eliminar la factura de compra.";
    }
}

// Lógica para LISTAR FACTURAS DE COMPRA
// Se ejecuta cuando se accede a la página
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $facturas = $facturaCompraModel->selectAll();
    include '../views/layouts/header.php';
    include '../views/facturaCompra/index.php';
    include '../views/layouts/footer.php';
}

// Lógica para mostrar el FORMULARIO DE CREAR FACTURA DE COMPRA
// Se ejecuta cuando se hace clic en el botón de agregar factura
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {

    // Necesitamos los proveedores, empleados y productos para llenar los selects en el formulario de creación
    // Proveedores
    require_once '../models/Proveedor.php';
    $proveedorModel = new Proveedor($db);
    $proveedores = $proveedorModel->selectAll();

    // Empleados
    require_once '../models/Empleado.php';
    $empleadoModel = new Empleado($db);
    $empleados = $empleadoModel->selectAll();

    // Productos
    require_once '../models/Producto.php';
    $productoModel = new Producto($db);
    $productos = $productoModel->selectAll();

    include '../views/layouts/header.php';
    include '../views/facturaCompra/crear.php';
    include '../views/layouts/footer.php';
}

// Lógica para mostrar el FORMULARIO DE EDITAR FACTURA DE COMPRA
// Se ejecuta cuando se hace clic en el botón de editar
elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $factura = $facturaCompraModel->getById($codigo);
    $productos_factura = $detalleModel->obtenerDetallesPorFactura($codigo);

    // Necesitamos los proveedores, empleados y productos para llenar los selects en el formulario de edición
    // Proveedores
    require_once '../models/Proveedor.php';
    $proveedorModel = new Proveedor($db);
    $proveedores = $proveedorModel->selectAll();

    // Empleados
    require_once '../models/Empleado.php';
    $empleadoModel = new Empleado($db);
    $empleados = $empleadoModel->selectAll();

    // Productos
    require_once '../models/Producto.php';
    $productoModel = new Producto($db);
    $productos = $productoModel->selectAll();

    include '../views/layouts/header.php';
    include '../views/facturaCompra/editar.php';
    include '../views/layouts/footer.php';
}
?>
