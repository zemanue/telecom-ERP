<?php
include '..\config\conexion_be.php';  // Incluir la conexión a la base de datos

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario y evitar inyecciones SQL
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $nif = mysqli_real_escape_string($conexion, $_POST['nif']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $poblacion = mysqli_real_escape_string($conexion, $_POST['poblacion']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $metodo_pago = mysqli_real_escape_string($conexion, $_POST['metodo_pago']);

    // Comprobar si alguno de los campos requeridos está vacío
    if (empty($telefono) || empty($nif) || empty($nombre) || empty($direccion) || empty($poblacion)) {
        echo "<script>alert('Por favor complete todos los campos obligatorios.'); window.location.href = 'clientes.php';</script>";
        exit();
    }

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO cliente (telefono, nif, nombre, direccion, poblacion, email, metodo_pago) 
            VALUES ('$telefono', '$nif', '$nombre', '$direccion', '$poblacion', '$email', '$metodo_pago')";

    // Verificar si la inserción fue exitosa
    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Cliente agregado exitosamente.'); window.location.href = 'clientes.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }
}
?>
