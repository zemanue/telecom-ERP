<!-- 
Este archivo maneja la lógica para la sección de clientes.
Es el intermediario entre los archivos de la vista (views/clientes) y el modelo (models/Cliente.php).
Reciben las peticiones del usuario, interactúan con los modelos para obtener o modificar datos, 
y luego seleccionan la vista que se debe mostrar al usuario.
    - La lógica para listar, crear, modificar y eliminar clientes utilizará
    los métodos definidos en el modelo Cliente.php (selectAll(), create(), update() y delete()).
-->

<?php
// Inclusión de archivos necesarios:
require_once '../config/database.php';
require_once '../models/Cliente.php';

// Instancia del modelo Cliente, pasando la conexión a la base de datos
$clienteModel = new Cliente($db);

// Lógica para listar clientes
// Se ejecuta cuando se accede a la página
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $clientes = $clienteModel->selectAll();
    include '../views/layouts/header.php';
    include '../views/clientes/index.php';
    include '../views/layouts/footer.php';
}

// Lógica para mostrar el formulario de crear cliente
// Se ejecuta cuando se hace clic en el botón de crear cliente
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    include '../views/layouts/header.php';
    include '../views/clientes/crear.php';
    include '../views/layouts/footer.php';
}

// Lógica para guardar un nuevo cliente
// Se ejecuta cuando se envía el formulario de creación
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $telefono = $_POST['telefono'];
    $nif = $_POST['nif'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $poblacion = $_POST['poblacion'];
    $email = $_POST['email'];
    $metodo_pago = $_POST['metodo_pago'];

    // Con este if, se intenta crear un nuevo cliente.
    // Utiliza el método create() del modelo Cliente.
    // Si se consigue, redirige de nuevo a la lista de clientes
    if ($clienteModel->create($telefono, $nif, $nombre, $direccion, $poblacion, $email, $metodo_pago)) {
        header('Location: cliente.php?action=list');
    } else {
        echo "Error al crear el cliente.";
    }
}

// Lógica para mostrar el formulario de editar cliente
// Se ejecuta cuando se hace clic en el botón de editar
elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $cliente = $clienteModel->getById($codigo);
    include '../views/layouts/header.php';
    include '../views/clientes/editar.php';
    include '../views/layouts/footer.php';
}

// Lógica para actualizar un cliente
// Este bloque se ejecuta cuando se envía el formulario de edición
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
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
    // Si se consigue, redirige de nuevo a la lista de clientes
    if ($clienteModel->update($codigo, $telefono, $nif, $nombre, $direccion, $poblacion, $email, $metodo_pago)) {
        header('Location: cliente.php?action=list');
    } else {
        echo "Error al actualizar el cliente.";
    }
}

// Lógica para eliminar un cliente
// Se ejecuta cuando se hace clic en el botón de eliminar
elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Con este if, se intenta eliminar un cliente.
    // Utiliza el método delete() del modelo Cliente.
    // Si se consigue, redirige de nuevo a la lista de clientes
    if ($clienteModel->delete($codigo)) {
        header('Location: cliente.php?action=list'); // Redirigir a la lista
    } else {
        echo "Error al eliminar el cliente.";
    }
}
?>