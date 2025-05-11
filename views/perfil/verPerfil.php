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

<!-- Contenido de perfil -->
<div class="container mt-5">
    <h2 class="mb-4">Perfil de Usuario</h2>
    <div class="card p-4">
        <p><strong>Nombre completo:</strong> <?= htmlspecialchars($nombreCompleto) ?></p>
        <p><strong>Nombre de usuario:</strong> <?= htmlspecialchars($nombreUsuario) ?></p>
        <p><strong>Correo electrónico:</strong> <?= htmlspecialchars($correo) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($telefono) ?></p>
        <a href="../home.php" class="btn btn-secondary mt-3">Volver al inicio</a>
    </div>
</div>

<?php
// Incluye el pie de página común
include '../layouts/footer.php';
?>
