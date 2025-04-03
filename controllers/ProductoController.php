<!-- 
Este archivo maneja la lógica para la sección de productos.
Es el intermediario entre los archivos de la vista (views/productos) y el modelo (models/Producto.php).
Reciben las peticiones del usuario, interactúan con los modelos para obtener o modificar datos, 
y luego seleccionan la vista que se debe mostrar al usuario.
    - La lógica para listar, crear, modificar y eliminar productos utilizará
    los métodos definidos en el modelo Cliente.php (selectAll(), create(), update() y delete()).
    - Este controlador es el responsable de que se añadan a todas las páginas el header y el footer comunes.
-->

<?php
// Inclusión de archivos necesarios:
require_once '../config/database.php';
require_once '../models/Producto.php';

// Instancia del modelo Producto, pasando la conexión a la base de datos
$productoModel = new Producto($db);


// Lógica para GUARDAR UN NUEVO PRODUCTO
// Se ejecuta cuando se envía el formulario de creación
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    error_log("Entrando en el bloque de creación de producto");
    $nombre = $_POST['nombre'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $iva = $_POST['IVA'];
    $codigo_proveedor = $_POST['codigo_proveedor'];
    $codigo_almacen = $_POST['codigo_almacen'];

    // Con este if, se intenta crear un nuevo cliente.
    // Utiliza el método create() del modelo Cliente.
    if ($productoModel->create($nombre, $precio_compra, $precio_venta, $iva, $codigo_proveedor, $codigo_almacen)) {	
        header('Location: ../controllers/ProductoController.php?action=list'); // Si se consigue, redirige de nuevo a la lista de productos
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al crear el producto.";
    }
}


// Lógica para ACTUALIZAR UN PRODUCTO
// Este bloque se ejecuta cuando se envía el formulario de edición
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    error_log("Entrando en el bloque de edición de cliente", 0); // Log para depuración
    $nombre = $_POST['nombre'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $iva = $_POST['IVA'];
    $codigo_proveedor = $_POST['codigo_proveedor'];
    $codigo_almacen = $_POST['codigo_almacen'];

    // Con este if, se intenta actualizar un proveedor.
    // Utiliza el método update() del modelo Proveedor.
    if ($productoModel->update($nombre, $precio_compra, $precio_venta, $iva, $codigo_proveedor, $codigo_almacen)) {
        header('Location: ../controllers/ProductoController.php?action=list'); // Si se consigue, redirige de nuevo a la lista de productos
        exit(); // Importante: detener la ejecución del script después de la redirección

    } else {
        echo "Error al actualizar el producto.";
    }
}


// Lógica para ELIMINAR UN PRODUCTO
// Se ejecuta cuando se hace clic en el botón de eliminar
elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Con este if, se intenta eliminar un producto.
    // Utiliza el método delete() del modelo Producto.
    // Si se consigue, redirige de nuevo a la lista de clientes
    if ($productoModel->delete($codigo)) {
        header('Location: ../controllers/ProductoController.php?action=list'); // Redirigir a la lista
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al eliminar el producto.";
    }
}


// Lógica para LISTAR PRODUCTOS
// Se ejecuta cuando se accede a la página
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $productos = $productoModel->selectAll();
    include '../views/layouts/header.php';
    include '../views/productos/index.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE CREAR PRODUCTOS
// Se ejecuta cuando se hace clic en el botón de crear producto
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    include '../views/layouts/header.php';
    include '../views/productos/crear.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE EDITAR PRODUCTO
// Se ejecuta cuando se hace clic en el botón de editar
elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $producto = $productoModel->getById($codigo);
    include '../views/layouts/header.php';
    include '../views/productos/editar.php';
    include '../views/layouts/footer.php';
}


?>