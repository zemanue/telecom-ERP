<!-- 
Este archivo maneja la lógica para la sección de empleados.
Es el intermediario entre los archivos de la vista (views/empleados) y el modelo (models/empleados.php).
Reciben las peticiones del usuario, interactúan con los modelos para obtener o modificar datos, 
y luego seleccionan la vista que se debe mostrar al usuario.
    - La lógica para listar, crear, modificar y eliminar empleado utilizará
    los métodos definidos en el modelo Empleado.php (selectAll(), create(), update() y delete()).
    - Este controlador es el responsable de que se añadan a todas las páginas el header y el footer comunes.
-->

<?php
// Inclusión de archivos necesarios:
require_once '../config/database.php';
require_once '../models/Empleado.php';

// Instancia del modelo Empleado, pasando la conexión a la base de datos
$empleadoModel = new Empleado($db);


// Lógica para GUARDAR UN NUEVO EMPLEADO
// Se ejecuta cuando se envía el formulario de creación
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    error_log("Entrando en el bloque de creación de empleado");
    $telefono = $_POST['telefono'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    // Con este if, se intenta crear un nuevo empleado.
    // Utiliza el método create() del modelo Empleado.
    if ($empleadoModel->create($telefono, $nombre, $email)) {
        header('Location: ../controllers/EmpleadoController.php?action=list'); // Si se consigue, redirige de nuevo a la lista de empleados
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al crear el empleado.";
    }
}


// Lógica para ACTUALIZAR UN EMPLEADO
// Este bloque se ejecuta cuando se envía el formulario de edición
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    error_log("Entrando en el bloque de edición de empleado", 0); // Log para depuración
    $codigo = $_POST['codigo'];
    $telefono = $_POST['telefono'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    // Con este if, se intenta actualizar un empleado.
    // Utiliza el método update() del modelo Empleado.
    if ($empleadoModel->update($codigo, $telefono, $nombre, $email)) {
        header('Location: ../controllers/EmpleadoController.php?action=list'); // Si se consigue, redirige de nuevo a la lista de empleados
        exit(); // Importante: detener la ejecución del script después de la redirección

    } else {
        echo "Error al actualizar el empleado.";
    }
}


// Lógica para ELIMINAR UN EMPLEADO
// Se ejecuta cuando se hace clic en el botón de eliminar
elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Con este if, se intenta eliminar un cliente.
    // Utiliza el método delete() del modelo Cliente.
    // Si se consigue, redirige de nuevo a la lista de clientes
    if ($empleadoModel->delete($codigo)) {
        header('Location: ../controllers/EmpleadoController.php?action=list'); // Redirigir a la lista
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al eliminar el empleado.";
    }
}


// Lógica para LISTAR EMPLEADOS
// Se ejecuta cuando se accede a la página
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $empleados = $empleadoModel->selectAll();
    include '../views/layouts/header.php';
    include '../views/empleados/index.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE CREAR EMPLEADO
// Se ejecuta cuando se hace clic en el botón de crear cliente
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    include '../views/layouts/header.php';
    include '../views/empleados/crear.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE EDITAR EMPLEADO
// Se ejecuta cuando se hace clic en el botón de editar
elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $empleado = $empleadoModel->getById($codigo);
    include '../views/layouts/header.php';
    include '../views/empleados/editar.php';
    include '../views/layouts/footer.php';
}


?>