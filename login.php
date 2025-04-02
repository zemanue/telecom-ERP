<?php
session_start();

// Incluir el archivo de configuración de la base de datos
require_once 'config/database.php';

$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

try {
    // Preparar la consulta para verificar el usuario
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $validar_login = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si se encuentra el usuario
    if ($validar_login) {
        // Verificar si la contraseña ingresada coincide con la encriptada
        if (password_verify($contrasena, $validar_login['contrasena'])) {
            $_SESSION['usuario'] = $validar_login['usuario']; // Guardar usuario
            $_SESSION['nombre_completo'] = $validar_login['nombre_completo']; // Guardar nombre

            header("Location: views/home.php");
            exit();
        } else {
            $_SESSION['error_login'] = "Contraseña incorrecta.";
            header("Location: index.php"); // Redirigir a la página de login
            exit();
        }
    } else {
        $_SESSION['error_login'] = "Usuario no encontrado.";
        header("Location: index.php"); // Redirigir a la página de login
        exit();
    }

} catch (PDOException $e) {
    // Manejar errores de la base de datos
    error_log("Error en login.php: " . $e->getMessage());
    $_SESSION['error_login'] = "Error interno al intentar iniciar sesión. Por favor, inténtelo de nuevo más tarde.";
    header("Location: index.php"); // Redirigir a la página de login
    exit();
}
