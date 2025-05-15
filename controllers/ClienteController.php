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
require_once '../models/Cliente.php';

// Instancia del modelo Cliente, pasando la conexión a la base de datos
$clienteModel = new Cliente($db);


// Lógica para GUARDAR UN NUEVO CLIENTE
// Se ejecuta cuando se envía el formulario de creación
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    error_log("Entrando en el bloque de creación de cliente");
    $telefono = $_POST['telefono'];
    $nif = $_POST['nif'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $poblacion = $_POST['poblacion'];
    $email = $_POST['email'];
    $metodo_pago = $_POST['metodo_pago'];

    // Con este if, se intenta crear un nuevo cliente.
    // Utiliza el método create() del modelo Cliente.
    if ($clienteModel->create($telefono, $nif, $nombre, $direccion, $poblacion, $email, $metodo_pago)) {
        header('Location: ../controllers/ClienteController.php?action=list'); // Si se consigue, redirige de nuevo a la lista de clientes
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al crear el cliente.";
    }
}


// Lógica para ACTUALIZAR UN CLIENTE
// Este bloque se ejecuta cuando se envía el formulario de edición
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    error_log("Entrando en el bloque de edición de cliente", 0); // Log para depuración
    $codigo = $_POST['codigo'];
    $telefono = $_POST['telefono'];
    $nif = $_POST['nif'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $poblacion = $_POST['poblacion'];
    $email = $_POST['email'];
    $metodo_pago = $_POST['metodo_pago'];

    // Con este if, se intenta actualizar un cliente.
    // Utiliza el método update() del modelo Cliente.
    if ($clienteModel->update($codigo, $telefono, $nif, $nombre, $direccion, $poblacion, $email, $metodo_pago)) {
        header('Location: ../controllers/ClienteController.php?action=list'); // Si se consigue, redirige de nuevo a la lista de clientes
        exit(); // Importante: detener la ejecución del script después de la redirección

    } else {
        echo "Error al actualizar el cliente.";
    }
}


// Lógica para ELIMINAR UN CLIENTE
// Se ejecuta cuando se hace clic en el botón de eliminar
elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Con este bloque try-catch, se intenta eliminar un cliente.
    // Se captura la excepción si tiene facturas asociadas.
    try {
        if ($clienteModel->delete($codigo)) {
            header('Location: ../controllers/ClienteController.php?action=list'); // Redirigir a la lista
            exit(); // Importante: detener la ejecución del script después de la redirección
        } else {
            throw new Exception("Error al eliminar el cliente.");
        }
    } catch (Exception $e) {
        // Mostrar una alerta Bootstrap si no se puede eliminar el cliente
        include '../views/layouts/header.php';
        echo '
            <div class="container mt-5">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Error al eliminar</h4>
                    <p>No se puede eliminar el cliente porque tiene facturas asociadas u otro error ha ocurrido.</p>
                    <hr>
                    <a href="javascript:history.back()" class="btn btn-outline-danger">Volver atrás</a>
                </div>
            </div>
        ';
        include '../views/layouts/footer.php';
        exit();
    }
}


// Lógica para LISTAR CLIENTES
// Se ejecuta cuando se accede a la página
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $clientes = $clienteModel->selectAll();
    include '../views/layouts/header.php';
    include '../views/clientes/index.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE CREAR CLIENTE
// Se ejecuta cuando se hace clic en el botón de crear cliente
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    include '../views/layouts/header.php';
    include '../views/clientes/crear.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE EDITAR CLIENTE
// Se ejecuta cuando se hace clic en el botón de editar
elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $cliente = $clienteModel->getById($codigo);
    include '../views/layouts/header.php';
    include '../views/clientes/editar.php';
    include '../views/layouts/footer.php';
}
?>
