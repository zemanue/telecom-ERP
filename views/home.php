<!-- 
La página a la que se llega después del login
    - En esta página se muestra un mensaje de bienvenida al usuario.
    - El header incluye la barra lateral y el menú de perfil desplegable.
    - El footer incluye los scripts de Bootstrap y JavaScript necesarios.
-->

<?php
    include 'layouts/header.php';

$nombre_completo = $_SESSION['nombre_completo']; // Obtener el nombre completo
?>


<div class="container mt-4">
    <h2>Bienvenido, <?php echo htmlspecialchars($nombre_completo); ?></h2>
    <p>Por favor, pulse sobre uno de los botones de la barra lateral para acceder a las diferentes secciones de la aplicación.</p>
    <hr>
    <h4>Los desarrolladores de Telecom:</h4>
    <ul>
        <li>Ángeles Trejo Croce</li>
        <li>Andrea Valbuena Lobatón</li>
        <li>Jose Manuel Redondo Conde</li>
        <li>Natalia Feria de la Cruz</li>
    </ul>
    <hr>
    <h4>Breve Descipción de la página de Telecom:</h4>

<p>Telecom ERP es una aplicación basada en web diseñada para gestionar varios aspectos de un negocio ficticio, incluyendo clientes, proveedores, empleados, productos y facturas.
Proporciona una interfaz fácil de usar para realizar operaciones CRUD y gestionar flujos de trabajo empresariales de manera eficiente.</p>
<h4>Desarrollado por:</h4>
<p> [Avalob](https://github.com/Avalob). <br>[nattfer](https://github.com/nattfer). <br>[ATreCro](https://github.com/ATreCro).  <br>[@zemanue](https://github.com/zemanue).</p>

<h4>Características</h4>
<p>
- Autenticación de usuario (inicio de sesión y registro).
<br>- Gestión de clientes, proveedores, empleados, almacenes y productos.
<br>- Creación, edición y eliminación de facturas para compras y ventas.
<br>- Validación para garantizar la integridad de los datos (por ejemplo, no productos sin proveedores o almacenes).
<br>- Diseño responsivo utilizando Bootstrap.
<br>- Organizado siguiendo el patrón de diseño Modelo-Vista-Controlador.</p>

<h4>Tecnologías empleadas</h4>
<p>- **Backend**: PHP, JavaScript</p>
<p>- **Frontend**: HTML, CSS, JavaScript, Bootstrap</p>
<p>- **Base de datos**: MySQL</p>
<p>- **Otras herramientas**: XAMPP (para la configuración del servidor local)</p>


</div>

<?php
    include 'layouts/footer.php';
?>