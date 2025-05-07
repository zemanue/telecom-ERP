<?php
require('../fpdf/fpdf.php');
require_once('../config/conexion_be.php');

if (!isset($_GET['codigo'])) {
    die("Código de factura no proporcionado.");
}

$codigoFactura = intval($_GET['codigo']);

// Obtener datos de la factura
$queryFactura = "SELECT fc.*, p.nombre AS nombre_proveedor, p.direccion AS direccion_proveedor, p.telefono, p.nif, e.nombre AS nombre_empleado 
                 FROM facturas_compra fc
                 JOIN proveedor p ON fc.codigo_proveedor = p.codigo
                 JOIN empleados e ON fc.codigo_empleado = e.codigo
                 WHERE fc.codigo = $codigoFactura";
$resultFactura = mysqli_query($conexion, $queryFactura);

if (!$resultFactura) {
    die("Error en la consulta de factura: " . mysqli_error($conexion));
}

$factura = mysqli_fetch_assoc($resultFactura);

// Obtener productos de la factura
$queryProductos = "SELECT pr.nombre, pr.precio_compra, pr.IVA, dfc.cantidad
                   FROM detalles_factura_compra dfc
                   JOIN productos pr ON dfc.codigo_producto = pr.codigo
                   WHERE dfc.codigo_factura = $codigoFactura";
$resultProductos = mysqli_query($conexion, $queryProductos);

if (!$resultProductos) {
    die("Error en la consulta de productos: " . mysqli_error($conexion));
}

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();

// Logo
$pdf->Image('../assets/img/logo.png', 10, 10, 30); // Ajusta la ruta y tamaño
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Telecom', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 6, 'Calle de la nada numero 1 1ºA', 0, 1, 'C');
$pdf->Ln(5);

// Número de factura arriba a la derecha
$pdf->SetY(10);
$pdf->SetX(-60);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Factura Nº: ' . $factura['codigo'], 0, 1, 'R');
$pdf->Ln(10);

// Datos proveedor y empleado
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 6, 'Fecha: ' . $factura['fecha'], 0, 1);
$pdf->Cell(100, 6, 'Proveedor: ' . $factura['nombre_proveedor'], 0, 1);
$pdf->Cell(100, 6, 'NIF: ' . $factura['nif'], 0, 1);
$pdf->Cell(100, 6, 'Dirección: ' . $factura['direccion_proveedor'], 0, 1);
$pdf->Cell(100, 6, 'Teléfono: ' . $factura['telefono'], 0, 1);
$pdf->Cell(100, 6, 'Empleado: ' . $factura['nombre_empleado'], 0, 1);
$pdf->Ln(10);

// Tabla de productos
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(70, 8, 'Producto', 1);
$pdf->Cell(30, 8, 'Cantidad', 1);
$pdf->Cell(30, 8, 'IVA (%)', 1);
$pdf->Cell(50, 8, 'Total (con IVA)', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$totalGeneral = 0;

while ($producto = mysqli_fetch_assoc($resultProductos)) {
    $nombre = $producto['nombre'];
    $cantidad = $producto['cantidad'];
    $precio = $producto['precio_compra'];
    $iva = $producto['IVA'];
    $subtotal = $cantidad * $precio;
    $totalConIVA = $subtotal + ($subtotal * $iva / 100);
    $totalGeneral += $totalConIVA;

    $pdf->Cell(70, 8, $nombre, 1);
    $pdf->Cell(30, 8, $cantidad, 1);
    $pdf->Cell(30, 8, $iva . '%', 1);
    $pdf->Cell(50, 8, number_format($totalConIVA, 2) . ' €', 1);
    $pdf->Ln();
}

// Total final
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(130, 8, 'Total Factura:', 1);
$pdf->Cell(50, 8, number_format($totalGeneral, 2) . ' €', 1);

$pdf->Output("I", "FacturaCompra_" . $factura['codigo'] . ".pdf");
?>
