<!-- 
La página a la que se llega después del login
    - En esta página se muestra un mensaje de bienvenida al usuario.
    - El header incluye la barra lateral y el menú de perfil desplegable.
    - El footer incluye los scripts de Bootstrap y JavaScript necesarios.
-->

<?php
    include 'layouts/header.php';

if (!isset($_SESSION['usuario'])) {
    echo '<script>
        alert("Debes iniciar sesión primero.");
        window.location="../index.php";
    </script>';
    exit();
}

$nombre_completo = $_SESSION['nombre_completo']; // Obtener el nombre completo
?>


<div class="container mt-4">
    <h2>Bienvenido, <?php echo htmlspecialchars($nombre_completo); ?></h2>
    <p>Por favor, pulse sobre uno de los botones de la barra lateral para acceder a las diferentes secciones de la aplicación.</p>
</div>

<?php
    include 'layouts/footer.php';
?>