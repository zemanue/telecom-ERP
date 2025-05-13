
<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

try {
    $stmt = $db->prepare("
        SELECT u.nombre_completo, u.usuario, u.correo, e.telefono
        FROM usuarios u
        LEFT JOIN empleados e ON u.usuario = e.codigo
        WHERE u.usuario = :usuario
    ");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuarioData) {
        echo "Error: No se encontraron datos del usuario.";
        exit();
    }

    $nombreCompleto = $usuarioData['nombre_completo'];
    $nombreUsuario = $usuarioData['usuario'];
    $correo = $usuarioData['correo'];
    $telefono = $usuarioData['telefono'] ?? 'No disponible';

} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    exit();
}

// Incluye el encabezado común
include '../layouts/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Perfil de Usuario</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5><strong>Nombre completo:</strong></h5>
                        <p><?= htmlspecialchars($nombreCompleto) ?></p>
                    </div>
                    <div class="mb-3">
                        <h5><strong>Nombre de usuario:</strong></h5>
                        <p><?= htmlspecialchars($nombreUsuario) ?></p>
                    </div>
                    <div class="mb-3">
                        <h5><strong>Correo electrónico:</strong></h5>
                        <p><?= htmlspecialchars($correo) ?></p>
                    </div>
                    <div class="mb-3">
                        <h5><strong>Teléfono:</strong></h5>
                        <p><?= htmlspecialchars($telefono) ?></p>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="../home.php" class="btn btn-secondary">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Incluye el pie de página común
include '../layouts/footer.php';
?>
