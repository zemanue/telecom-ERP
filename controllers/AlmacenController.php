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
require_once '../models/Almacen.php';

// Instancia del modelo Almacén, pasando la conexión a la base de datos
$almacenModel = new Almacen($db);


// Lógica para GUARDAR UN NUEVO ALMACENw
// Se ejecuta cuando se envía el formulario de creación
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    error_log("Entrando en el bloque de creación de cliente");
    $nombre = $_POST['nombre_almacen'];
    $ubicacion = $_POST['ubicacion'];
    
    // Con este if, se intenta crear un nuevo almacén.
    // Utiliza el método create() del modelo Almacén.
    if ($almacenModel->create($nombre, $ubicacion)) {
        header('Location: ../controllers/AlmacenController.php?action=list'); // Si se consigue, redirige de nuevo a la lista de clientes
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al crear el almacén.";
    }
}


// Lógica para ACTUALIZAR UN ALMACÉN
// Este bloque se ejecuta cuando se envía el formulario de edición
// Recupera los datos del formulario enviados con el método POST
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    error_log("Entrando en el bloque de edición de almacén", 0); // Log para depuración
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre_almacen'];
    $ubicacion = $_POST['ubicacion'];

    // Con este if, se intenta actualizar un almacén.
    // Utiliza el método update() del modelo Almacén.
    if ($almacenModel->update($codigo, $nombre, $ubicacion)) {
        header('Location: ../controllers/AlmacenController.php?action=list'); // Si se consigue, redirige de nuevo a la lista de almacenes
        exit(); // Importante: detener la ejecución del script después de la redirección

    } else {
        echo "Error al actualizar el alamacén.";
    }
}


// Lógica para ELIMINAR UN ALMACÉN
// Se ejecuta cuando se hace clic en el botón de eliminar
elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Con este if, se intenta eliminar un almacén.
    // Utiliza el método delete() del modelo Almacén.
    // Si se consigue, redirige de nuevo a la lista de almacenes
    if ($almacenModel->delete($codigo)) {
        header('Location: ../controllers/AlmacenController.php?action=list'); // Redirigir a la lista
        exit(); // Importante: detener la ejecución del script después de la redirección
    } else {
        echo "Error al eliminar el almacén.";
    }
}


// Lógica para LISTAR ALMACÉNES
// Se ejecuta cuando se accede a la página
if (isset($_GET['action']) && $_GET['action'] == 'list') {
    $almacenes = $almacenModel->selectAll();
    include '../views/layouts/header.php';
    include '../views/almacenes/index.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE CREAR ALMACÉN
// Se ejecuta cuando se hace clic en el botón de crear almacén
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    include '../views/layouts/header.php';
    include '../views/almacenes/crear.php';
    include '../views/layouts/footer.php';
}


// Lógica para mostrar el FORMULARIO DE EDITAR ALMACÉN
// Se ejecuta cuando se hace clic en el botón de editar
elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $almacen = $almacenModel->getById($codigo);
    include '../views/layouts/header.php';
    include '../views/almacenes/editar.php';
    include '../views/layouts/footer.php';
}


?>