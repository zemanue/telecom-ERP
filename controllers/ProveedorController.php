<!-- 
Este archivo maneja la lógica para la sección de clientes.
Es el intermediario entre los archivos de la vista (views/clientes) y el modelo (models/Cliente.php).
Reciben las peticiones del usuario, interactúan con los modelos para obtener o modificar datos, 
y luego seleccionan la vista que se debe mostrar al usuario.
    - La lógica para listar, crear, modificar y eliminar clientes utilizará
    los métodos definidos en el modelo Cliente.php (selectAll(), create(), update() y delete()).
    - Este controlador es el responsable de que se añadan a todas las páginas el header y el footer comunes.
-->

<?php
// Inclusión de archivos necesarios:
require_once '../config/database.php';
require_once '../models/proveedor.php';

// Instancia del modelo Cliente, pasando la conexión a la base de datos
$proveedorModel = new Proveedor($db);


// Lógica para GUARDAR UN NUEVO CLIENTE
// Se ejecuta cuando se envía el formulario de creación
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    error_log("Entrando en el bloque de creación de cliente");
    $telefono = $_POST['telefono'];
    $nif = $_POST['nif'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $poblacion = $_POST['poblacion'];
    $email = $_POST['email'];
    $deuda_existente = $_POST['deuda_existente'];

    // Con este if, se intenta crear un nuevo cliente.
    // Utiliza el método create() del modelo Cliente.
    if ($proveedorModel->create($telefono, $nif, $nombre, $direccion, $poblacion, $email, $deuda_existente)) {
        header('Location: ../controllers/ProveedorController.php?action=list'); // Si se consigue, redirige de nuevo a la lista de clientes
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al crear el proveedor.";
    }
}


// Lógica para ACTUALIZAR UN CLIENTE
// Este bloque se ejecuta cuando se envía el formulario de edición
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    error_log("Entrando en el bloque de edición de proveedor", 0); // Log para depuración
    $codigo = $_POST['codigo'];
    $telefono = $_POST['telefono'];
    $nif = $_POST['nif'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $poblacion = $_POST['poblacion'];
    $email = $_POST['email'];
    $deuda_existente = $_POST['deuda_existente'];

    // Con este if, se intenta actualizar un cliente.
    // Utiliza el método update() del modelo Cliente.
    if ($proveedorModel->update($codigo, $telefono, $nif, $nombre, $direccion, $poblacion, $email, $deuda_existente)) {
        header('Location: ../controllers/ProveedorController.php?action=list'); // Si se consigue, redirige de nuevo a la lista de clientes
        exit(); // Importante: detener la ejecución del script después de la redirección

    } else {
        echo "Error al actualizar el proveedor.";
    }
}


// Lógica para ELIMINAR UN CLIENTE
// Se ejecuta cuando se hace clic en el botón de eliminar
elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Con este if, se intenta eliminar un cliente.
    // Utiliza el método delete() del modelo Cliente.
    // Si se consigue, redirige de nuevo a la lista de clientes
    if ($proveedorModel->delete($codigo)) {
        header('Location: ../controllers/ProveedorController.php?action=list'); // Redirigir a la lista
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al eliminar el proveedor.";
    }
}


// Lógica para LISTAR CLIENTES
// Se ejecuta cuando se accede a la página
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $proveedores = $proveedorModel->selectAll();
    include '../views/layouts/header.php';
    include '../views/proveedores/index.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE CREAR CLIENTE
// Se ejecuta cuando se hace clic en el botón de crear cliente
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    include '../views/layouts/header.php';
    include '../views/proveedores/crear.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE EDITAR CLIENTE
// Se ejecuta cuando se hace clic en el botón de editar
elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $proveedor = $proveedorModel->getById($codigo);
    include '../views/layouts/header.php';
    include '../views/proveedores/editar.php';
    include '../views/layouts/footer.php';
}


?>