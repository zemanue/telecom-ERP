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
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    error_log("Entrando en el bloque de creación de factura de venta");

    $fecha = $_POST['fecha'];
    $direccion = $_POST['direccion'];
    $codigo_cliente = $_POST['codigo_cliente'];
    $codigo_empleado = $_POST['codigo_empleado'];
    $metodo_pago = $_POST['metodo_pago'];
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];

    try {
        $db->beginTransaction();

        // Crear factura de venta
        $codigo_factura = $facturaVentaModel->create($fecha, $direccion, $codigo_cliente, $codigo_empleado, $metodo_pago);

        if ($codigo_factura) {
            // Insertar detalles y reducir stock
            foreach ($productos as $index => $producto_id) {
                $cantidad = $cantidades[$index];

                // Insertar detalle de venta
                $detalleModel->insertarDetalle($codigo_factura, $producto_id, $cantidad);

                // Reducir stock solo si la factura es "Emitida"
                if ($_POST['estado'] == 'Emitida') {
                    $productoModel->reducirStock($producto_id, $cantidad);
                }
            }

            $db->commit();
            header('Location: ../controllers/FacturaVentaController.php?action=list');
            exit();
        } else {
            $db->rollBack();
            echo "Error al crear la factura de venta.";
        }
    } catch (Exception $e) {
        $db->rollBack();
        echo "Error al procesar la factura de venta: " . $e->getMessage();
    }
}

// Lógica para ACTUALIZAR UNA FACTURA DE VENTA
// Este bloque se ejecuta cuando se envía el formulario de edición
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    error_log("Entrando en el bloque de edición de factura de venta", 0);
    
    $codigo = $_POST['codigo'];

    // Verificar si la factura existe y su estado, para impedir la edición si no está en "Borrador"
    $facturaExistente = $facturaVentaModel->getById($codigo);
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
    $codigo_cliente = $_POST['codigo_cliente'];
    $codigo_empleado = $_POST['codigo_empleado'];
    $metodo_pago = $_POST['metodo_pago'];
    $estado = $_POST['estado'];
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];

    try {
        $db->beginTransaction();

        // Actualizamos la factura en la tabla principal
        if ($facturaVentaModel->update($codigo, $fecha, $direccion, $codigo_cliente, $codigo_empleado, $metodo_pago, $estado)) {
            // Primero obtener los detalles existentes de la factura
            $detallesAnteriores = $detalleModel->obtenerDetallesPorFactura($codigo);

            // Después eliminamos los detalles existentes
            $detalleModel->eliminarDetallesPorFactura($codigo);

            // Insertar los nuevos detalles y actualizar stock
            foreach ($productos as $index => $producto_id) {
                $cantidadNueva = $cantidades[$index];
                $detalleModel->insertarDetalle($codigo, $producto_id, $cantidadNueva);

                // Buscar la cantidad anterior de cada producto en los detalles anteriores
                $cantidadAnterior = 0;
                foreach ($detallesAnteriores as $detalle) {
                    if ($detalle['codigo_producto'] == $producto_id) {
                        $cantidadAnterior = $detalle['cantidad'];
                        break;
                    }
                }

                // Calcular la diferencia y actualizar el stock
                $diferencia = $cantidadNueva - $cantidadAnterior;
                if ($_POST['estado'] == 'Emitida') {
                    $productoModel->reducirStock($producto_id, $diferencia);
                }
            }

            $db->commit();
            // Redirigimos a la lista de facturas
            header('Location: ../controllers/FacturaVentaController.php?action=list');
            exit();
        } else {
            $db->rollBack();
            echo "Error al intentar actualizar la factura de venta.";
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

    // Obtener estado anterior ANTES de actualizar
    $factura = $facturaVentaModel->getById($codigo);
    $estadoAnterior = $factura['estado'];

    // Obtener los detalles de la factura
    $detalles = $detalleModel->obtenerDetallesPorFactura($codigo);

    // Intentamos cambiar el estado de la factura
    if ($facturaVentaModel->changeStatus($codigo, $estadoNuevo)) {

        // Si pasa de Emitida a Anulada → reponer stock
        if ($estadoAnterior === 'Emitida' && $estadoNuevo === 'Anulada') {
            foreach ($detalles as $detalle) {
                $productoModel->aumentarStock($detalle['codigo_producto'], $detalle['cantidad']);
            }
        }

        // Si pasa de Borrador a Emitida → descontar stock
        elseif ($estadoAnterior === 'Borrador' && $estadoNuevo === 'Emitida') {
            foreach ($detalles as $detalle) {
                $productoModel->reducirStock($detalle['codigo_producto'], $detalle['cantidad']);
            }
        }

        // Redirigir a la lista
        header('Location: ../controllers/FacturaVentaController.php?action=list');
        exit();

    } else {
        echo "Error al cambiar el estado de la factura de venta.";
    }
}

// Lógica para ELIMINAR UNA FACTURA
// Se ejecuta cuando se hace clic en el botón de eliminar
elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])) {
    
    // Verificar si la factura existe y su estado, para impedir la eliminación si no está en "Borrador"
    $codigo = $_GET['codigo'];
    $facturaExistente = $facturaVentaModel->getById($codigo);
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
    $facturas = $facturaVentaModel->selectAllWithDetails();

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
