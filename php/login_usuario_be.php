<?php

    include 'conexion_be.php';

    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // La variable para verificar que el usuario introducido existe en la base de datos
    $validar_login = mysqli_query ($conexion, "SELECT * FROM usuarios WHERE usuario = '$usuario' and contrasena = '$contrasena'");

    // Si hay un error en la consulta (por ejemplo, si la tabla o la columna no existen)
    if (!$validar_login) {
        exit('Error en la consulta: ' . mysqli_error($conexion));
    }

    // Si el usuario existe (si hay al menos una fila que cumple con la condición)
    if(mysqli_num_rows($validar_login) > 0) {
        header("location: home.php");
        exit;
    } else {
        echo'
        <script>
            alert ("Usuario no existe.);
            windows.location ="../index.php";
        </script>
        ';
        exit;
    }

?>