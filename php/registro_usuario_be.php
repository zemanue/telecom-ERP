<?php

include 'conexion_be.php';

$nombre_completo = $_POST['nombre_completo'];
$correo = $_POST['correo'];
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

// Para verificar que se han rellenado todos los campos
if (empty($nombre_completo) || empty($correo) || empty($usuario) || empty($contrasena)) {
    echo '
    <script>
        alert("Asegúrate de rellenar todos los campos");
        window.location="../index.php";
    </script>
    ';
    exit();
}

// La variable para verificar que el correo introducido no coincida con uno ya registrado
$verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo = '$correo'");

// Si hay un error en la consulta (por ejemplo, si la tabla o la columna no existen)
if (!$verificar_correo) {
    exit('Error en la consulta: ' . mysqli_error($conexion));
}

// Si el correo ya está registrado (si hay al menos una fila que cumple con la condición)
if (mysqli_num_rows($verificar_correo) > 0) {
    echo '
    <script>
        alert("Correo ya registrado anteriormente , prueba con otro.");
        window.location="../index.php";
    </script>
    ';
    exit();
}

// La variable para verificar que el usuario introducido no coincida con uno ya registrado
$verificar_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario='$usuario'");

// Misma comprobación de error en la consulta
if (!$verificar_usuario) {
    exit('Error en la consulta: ' . mysqli_error($conexion));
}

// Si el usuario ya está registrado 
if (mysqli_num_rows($verificar_usuario) > 0) {
    echo '
    <script>
        alert("Usuario ya registrado anteriormente , prueba con otro.");
        window.location="../index.php";
    </script>
    ';
    exit();
}

// Insertar el nuevo usuario
$query_usuario = "INSERT INTO usuarios(usuario, contrasena, nombre_completo, correo)
    VALUES('$usuario', '$contrasena', '$nombre_completo', '$correo')";

$ejecutar_usuario = mysqli_query($conexion, $query_usuario);

if ($ejecutar_usuario) {
    // Insertar nombre y correo en la tabla empleados
    $query_empleado = "INSERT INTO empleados(nombre, email) VALUES('$nombre_completo', '$correo')";
    $ejecutar_empleado = mysqli_query($conexion, $query_empleado);

    if ($ejecutar_empleado) {
        echo '
        <script>
            alert("Usuario y empleado almacenados correctamente.");
            window.location = "../index.php";
        </script>
        ';
    } else {
        echo '
        <script>
            alert("Usuario almacenado, pero error al registrar en empleados.");
            window.location = "../index.php";
        </script>
        ';
    }
} else {
    echo '
    <script>
        alert("Error al registrar el usuario, intentelo de nuevo.");
        window.location = "../index.php";
    </script>
    ';
}

mysqli_close($conexion);
?>