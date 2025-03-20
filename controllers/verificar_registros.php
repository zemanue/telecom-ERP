<!-- Este archivo se encargará de consultar en la base de datos 
si hay proveedores o almacenes. -->

<?php
session_start();
include '../models/conexion_be.php';

header('Content-Type: application/json');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$tabla = $_GET['tabla'] ?? '';

if ($tabla === 'proveedor' || $tabla === 'almacen') {
    $query = $conexion->prepare("SELECT COUNT(*) as total FROM $tabla");
    $query->execute();
    $resultado = $query->get_result()->fetch_assoc();
    echo json_encode(['total' => $resultado['total']]);
} else {
    echo json_encode(['error' => 'Tabla no válida']);
}
