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
$productoModel = new Producto($db);

// Lógica para GUARDAR UNA NUEVA FACTURA DE COMPRA
// Se ejecuta cuando se envía el formulario de creación
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    error_log("Entrando en el bloque de creación de factura de compra");

    $fecha = $_POST['fecha'];
    $direccion = $_POST['direccion'];
    $codigo_proveedor = $_POST['codigo_proveedor'];
    $codigo_empleado = $_POST['codigo_empleado'];
    $metodo_pago = $_POST['metodo_pago'];
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];
    $estado = $_POST['estado'] ?? 'Borrador'; // NUEVO: Estado por defecto

    try {
        $db->beginTransaction();

        if ($facturaCompraModel->create($fecha, $direccion, $codigo_proveedor, $codigo_empleado, $metodo_pago, $estado)) {
            $codigo_factura = $db->lastInsertId();

            foreach ($productos as $index => $producto_id) {
                $cantidad = $cantidades[$index];
                $detalleModel->insertarDetalle($codigo_factura, $producto_id, $cantidad);

                // NUEVO: Solo actualizar stock si no está en borrador
                if ($estado !== 'Borrador') {
                    $productoModel->aumentarStock($producto_id, $cantidad);
                }
            }

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
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    error_log("Entrando en el bloque de edición de factura de compra", 0);

    $codigo = $_POST['codigo'];

    $facturaExistente = $facturaCompraModel->getById($codigo);
    if ($facturaExistente && $facturaExistente['estado'] !== 'Borrador') {
        include '../views/layouts/header.php';
        echo '
        <div class="container mt-5">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Factura no editable</h4>
                <p>No se puede editar la factura porque tiene un estado distinto a "Borrador".</p>
                <p>Estado actual: ' . $facturaExistente['estado'] . '</p>
                <p>Prueba a cambiar el estado a "Borrador" y vuelve a intentarlo.</p>
                <hr>
                <a href="javascript:history.back()" class="btn btn-outline-danger">Volver atrás</a>
            </div>
        </div>
        ';
        include '../views/layouts/footer.php';
        exit();
    }

    $fecha = $_POST['fecha'];
    $direccion = $_POST['direccion'];
    $codigo_proveedor = $_POST['codigo_proveedor'];
    $codigo_empleado = $_POST['codigo_empleado'];
    $metodo_pago = $_POST['metodo_pago'];
    $estado = $_POST['estado'];
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];

    try {
        $db->beginTransaction();

        if ($facturaCompraModel->update($codigo, $fecha, $direccion, $codigo_proveedor, $codigo_empleado, $metodo_pago, $estado)) {
            $detallesAnteriores = $detalleModel->obtenerDetallesPorFactura($codigo);
            $detalleModel->eliminarDetallesPorFactura($codigo);

            foreach ($productos as $index => $producto_id) {
                $cantidadNueva = $cantidades[$index];
                $detalleModel->insertarDetalle($codigo, $producto_id, $cantidadNueva);

                $cantidadAnterior = 0;
                foreach ($detallesAnteriores as $detalle) {
                    if ($detalle['codigo_producto'] == $producto_id) {
                        $cantidadAnterior = $detalle['cantidad'];
                        break;
                    }
                }

                $diferencia = $cantidadNueva - $cantidadAnterior;

                // NUEVO: Solo actualizar stock si no está en Borrador
                if ($estado !== 'Borrador') {
                    $productoModel->aumentarStock($producto_id, $diferencia);
                }
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

// Lógica para CAMBIAR EL ESTADO DE UNA FACTURA
// Se ejecuta cuando se envía el formulario de cambiar estado
elseif (isset($_POST['action']) && $_POST['action'] == 'change_status') {
    $codigo = $_POST['codigo'];
    $estadoNuevo = $_POST['estado'];

    $factura = $facturaCompraModel->getById($codigo);
    $detalles = $detalleModel->obtenerDetallesPorFactura($codigo);

    if ($factura) {
        $estadoAnterior = $factura['estado'];

        // Si pasa de Borrador a Emitida, actualizar stock
        if ($estadoAnterior === 'Borrador' && $estadoNuevo === 'Emitida') {
            foreach ($detalles as $detalle) {
                $productoModel->aumentarStock($detalle['codigo_producto'], $detalle['cantidad']);
            }
        }

        // Si pasa a Anulada, devolver stock (restar)
        elseif ($estadoNuevo === 'Anulada') {
            foreach ($detalles as $detalle) {
                $productoModel->aumentarStock($detalle['codigo_producto'], -1 * $detalle['cantidad']);
            }
        }

        if ($facturaCompraModel->changeStatus($codigo, $estadoNuevo)) {
            header('Location: ../controllers/FacturaCompraController.php?action=list');
            exit();
        } else {
            echo "Error al cambiar el estado de la factura de compra.";
        }
    } else {
        echo "Factura no encontrada.";
    }
}

// Lógica para ELIMINAR UNA FACTURA
// Se ejecuta cuando se hace clic en el botón de eliminar
elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $facturaExistente = $facturaCompraModel->getById($codigo);
    if ($facturaExistente && $facturaExistente['estado'] !== 'Borrador') {
        include '../views/layouts/header.php';
        echo '
        <div class="container mt-5">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Factura no eliminable</h4>
                <p>No se puede eliminar la factura porque tiene un estado distinto a "Borrador".</p>
                <p>Estado actual: ' . $facturaExistente['estado'] . '</p>
                <p>Prueba a cambiar el estado a "Borrador" y vuelve a intentarlo.</p>
                <hr>
                <a href="javascript:history.back()" class="btn btn-outline-danger">Volver atrás</a>
            </div>
        </div>
        ';
        include '../views/layouts/footer.php';
        exit();
    }

    if ($facturaCompraModel->delete($codigo)) {
        header('Location: ../controllers/FacturaCompraController.php?action=list');
        exit();
    } else {
        echo "Error al eliminar la factura de compra.";
    }
}

// Lógica para LISTAR FACTURAS DE COMPRA
// Se ejecuta cuando se accede a la página
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $facturas = $facturaCompraModel->selectAllWithDetails();

    include '../views/layouts/header.php';
    include '../views/facturaCompra/index.php';
    include '../views/layouts/footer.php';
}

// Lógica para mostrar el FORMULARIO DE CREAR FACTURA DE COMPRA
// Se ejecuta cuando se hace clic en el botón de agregar factura
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    require_once '../models/Proveedor.php';
    $proveedorModel = new Proveedor($db);
    $proveedores = $proveedorModel->selectAll();

    require_once '../models/Empleado.php';
    $empleadoModel = new Empleado($db);
    $empleados = $empleadoModel->selectAll();

    require_once '../models/Producto.php';
    $productoModel = new Producto($db);
    $productos = $productoModel->selectAll();

    include '../views/layouts/header.php';
    include '../views/facturaCompra/crear.php';
    include '../views/layouts/footer.php';
}

// Lógica para mostrar el FORMULARIO DE EDITAR FACTURA DE COMPRA
// Se ejecuta cuando se hace clic en el botón de editar
elseif (isset($_GET['action']) && isset($_GET['codigo']) && $_GET['action'] == 'edit') {
    $codigo = $_GET['codigo'];
    $factura = $facturaCompraModel->getById($codigo);
    $productos_factura = $detalleModel->obtenerDetallesPorFactura($codigo);

    require_once '../models/Proveedor.php';
    $proveedorModel = new Proveedor($db);
    $proveedores = $proveedorModel->selectAll();

    require_once '../models/Empleado.php';
    $empleadoModel = new Empleado($db);
    $empleados = $empleadoModel->selectAll();

    require_once '../models/Producto.php';
    $productoModel = new Producto($db);
    $productos = $productoModel->selectAll();

    include '../views/layouts/header.php';
    include '../views/facturaCompra/editar.php';
    include '../views/layouts/footer.php';
}
?>
