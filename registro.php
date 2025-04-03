<?php
session_start();

// Incluir el archivo de configuración de la base de datos
require_once 'config/database.php';

$nombre_completo = $_POST['nombre_completo'];
$correo = $_POST['correo'];
$usuario = $_POST['usuario'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT); // 🔒 Encriptar la contraseña

try {
    // Preparar la consulta para verificar si el correo ya existe
    $stmt_correo = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo");
    $stmt_correo->bindParam(':correo', $correo);
    $stmt_correo->execute();
    $correo_existente = $stmt_correo->fetchColumn();

    // Si el correo ya está registrado
    if ($correo_existente > 0) {
        $_SESSION['error_registro'] = "Correo ya registrado anteriormente, intente introducir otro.";
        header("Location: index.php");
        exit();
    }

    // Preparar la consulta para verificar si el usuario ya existe
    $stmt_usuario = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario");
    $stmt_usuario->bindParam(':usuario', $usuario);
    $stmt_usuario->execute();
    $usuario_existente = $stmt_usuario->fetchColumn();

    // Si el usuario ya está registrado
    if ($usuario_existente > 0) {
        $_SESSION['error_registro'] = "Usuario ya registrado anteriormente, pruebe con otro.";
        header("Location: index.php");
        exit();
    }

    // Insertar el nuevo usuario
    $stmt_insert_usuario = $db->prepare("INSERT INTO usuarios (usuario, contrasena, nombre_completo, correo) VALUES (:usuario, :contrasena, :nombre_completo, :correo)");
    $stmt_insert_usuario->bindParam(':usuario', $usuario);
    $stmt_insert_usuario->bindParam(':contrasena', $contrasena);
    $stmt_insert_usuario->bindParam(':nombre_completo', $nombre_completo);
    $stmt_insert_usuario->bindParam(':correo', $correo);
    $resultado_usuario = $stmt_insert_usuario->execute();

    if ($resultado_usuario) {
        // Insertar nombre y correo en la tabla empleados
        $stmt_insert_empleado = $db->prepare("INSERT INTO empleados (nombre, email) VALUES (:nombre_completo, :correo)");
        $stmt_insert_empleado->bindParam(':nombre_completo', $nombre_completo);
        $stmt_insert_empleado->bindParam(':correo', $correo);
        $resultado_empleado = $stmt_insert_empleado->execute();

        if ($resultado_empleado) {
            $_SESSION['registro_exitoso'] = "Usuario y empleado almacenados correctamente.";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error_registro'] = "Usuario almacenado, pero error al registrar en empleados.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['error_registro'] = "Error al registrar el usuario, inténtelo de nuevo.";
        header("Location: index.php");
        exit();
    }

} catch (PDOException $e) {
    // Manejar errores de la base de datos
    error_log("Error en registro.php: " . $e->getMessage());
    $_SESSION['error_registro'] = "Error interno al intentar registrar el usuario. Por favor, inténtelo de nuevo más tarde.";
    header("Location: ../index.php");
    exit();
}
?>