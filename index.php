<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
</head>

<body>
    <main>
        <div class="contenedor_todo">
            <div class="caja_trasera">
                <div class="caja_trasera_login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para entrar en la página</p>
                    <button id="btn_iniciar-sesion">Iniciar sesión</button>
                </div>
                <div class="caja_trasera_register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para que puedas iniciar sesión</p>
                    <button id="btn_registrarse">Registrarse</button>
                </div>
            </div>
            <div class="contenedor_login_register">
                <form action="login.php" method="POST" class="formulario_login">
                    <h2>Inicia Sesión</h2>
                    <?php
                    session_start();
                    // Si el usuario intenta iniciar sesión y hay un error, se muestra el mensaje en un cuadro rojo
                    if (isset($_SESSION['error_login'])) {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $_SESSION['error_login'];
                        echo '</div>';
                        unset($_SESSION['error_login']); // Limpiar el mensaje de error después de mostrarlo
                    }
                    ?>
                    <input type="text" placeholder="Usuario" name="usuario" required>
                    <input type="password" placeholder="Contraseña" name="contrasena" required>
                    <button>Entrar</button>
                </form>

                <form action="registro.php" method="POST" class="formulario_register">
                    <h2>Registrarse</h2>
                    <?php
                    // Si el usuario intenta registrarse y hay un error, se muestra el mensaje en un cuadro rojo
                    if (isset($_SESSION['error_registro'])) {
                        echo '<div class="alert alert-danger" role="alert">';
                        echo $_SESSION['error_registro'];
                        echo '</div>';
                        unset($_SESSION['error_registro']); // Limpiar el mensaje de error
                    }
                    // Si el registro fue exitoso, se muestra un mensaje de éxito en un cuadro verde
                    if (isset($_SESSION['registro_exitoso'])) {
                        echo '<div class="alert alert-success" role="alert">';
                        echo $_SESSION['registro_exitoso'];
                        echo '</div>';
                        unset($_SESSION['registro_exitoso']); // Limpiar el mensaje de éxito
                    }
                    ?>
                    <input type="text" placeholder="Nombre completo" name="nombre_completo" required>
                    <input type="text" placeholder="Correo electrónico" name="correo" required>
                    <input type="text" placeholder="Usuario" name="usuario" required>
                    <input type="password" placeholder="Contraseña" name="contrasena" required>
                    <button>Registrarse</button>
                </form>
            </div>
        </div>
    </main>
    <script src="assets/js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>