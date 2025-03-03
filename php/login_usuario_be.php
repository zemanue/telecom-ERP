<?php
    session_start();
    include 'conexion_be.php';

    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // La variable para verificar que el usuario introducido existe en la base de datos
    $validar_login = mysqli_query ($conexion, "SELECT * FROM usuarios WHERE usuario = '$usuario'");

    // Si hay un error en la consulta (por ejemplo, si la tabla o la columna no existen)
    if (!$validar_login) {
        exit('Error en la consulta: ' . mysqli_error($conexion));
    }

    // Si el usuario existe (si hay al menos una fila que cumple con la condición)
    if (mysqli_num_rows($validar_login) > 0) {
        $datos_usuario = mysqli_fetch_assoc($validar_login);
    
        // Verificar si la contraseña ingresada coincide con la encriptada
        if (password_verify($contrasena, $datos_usuario['contrasena'])) {
            $_SESSION['usuario'] = $datos_usuario['usuario']; // Guardar usuario
            $_SESSION['nombre_completo'] = $datos_usuario['nombre_completo']; // Guardar nombre
    
            header("Location: home.php");
            exit();
        } else {
            echo '<script>
                alert("Contraseña incorrecta.");
                window.location="../index.php";
            </script>';
            exit();
        }
    } else {
        echo '<script>
            alert("Usuario no encontrado.");
            window.location="../index.php";
        </script>';
        exit();
    }
    
    mysqli_close($conexion);