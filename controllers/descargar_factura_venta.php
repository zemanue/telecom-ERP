<?php
require('../fpdf/fpdf.php');
require_once('../config/conexion_be.php');

if (!isset($_GET['codigo'])) {
    die("Código de factura no proporcionado.");
}

$codigoFactura = intval($_GET['codigo']);

// Obtener datos de la factura
$queryFactura = "SELECT fv.*, c.nombre AS nombre_cliente, c.direccion AS direccion_cliente, c.telefono, c.nif, c.metodo_pago, e.nombre AS nombre_empleado 
                FROM facturas_venta fv
                JOIN cliente c ON fv.codigo_cliente = c.codigo
                JOIN empleados e ON fv.codigo_empleado = e.codigo
                WHERE fv.codigo = $codigoFactura";
$resultFactura = mysqli_query($conexion, $queryFactura);

if (!$resultFactura) {
    die("Error en la consulta de factura: " . mysqli_error($conexion));
}

$factura = mysqli_fetch_assoc($resultFactura);

// Obtener productos de la factura
$queryProductos = "SELECT pr.nombre, pr.precio_venta, pr.IVA, dfv.cantidad
                FROM detalles_factura_venta dfv
                JOIN productos pr ON dfv.codigo_producto = pr.codigo
                WHERE dfv.codigo_factura = $codigoFactura";
$resultProductos = mysqli_query($conexion, $queryProductos);

if (!$resultProductos) {
    die("Error en la consulta de productos: " . mysqli_error($conexion));
}

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();

// Logo
$pdf->Image('../assets/img/logo2.png', 10, 10, 30); // Ajusta la ruta y tamaño
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Telecom', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 6, utf8_decode('Calle de la nada numero 1 1ºA'), 0, 1, 'C');
$pdf->Ln(5); // Espacio entre el logo y la factura

// Número de factura arriba a la derecha
$pdf->SetY(10);
$pdf->SetX(-60);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, utf8_decode('Factura Nº: ' . $factura['codigo']), 0, 1, 'R');
$pdf->Ln(10);

// Datos cliente y empleado
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 6, utf8_decode('Fecha: ' . $factura['fecha']), 0, 1);
$pdf->Cell(100, 6, utf8_decode('Cliente: ' . $factura['nombre_cliente']), 0, 1);
$pdf->Cell(100, 6, utf8_decode('NIF: ' . $factura['nif']), 0, 1);
$pdf->Cell(100, 6, utf8_decode('Dirección: ' . $factura['direccion_cliente']), 0, 1);
$pdf->Cell(100, 6, utf8_decode('Teléfono: ' . $factura['telefono']), 0, 1);
$pdf->Cell(100, 6, utf8_decode('Empleado: ' . $factura['nombre_empleado']), 0, 1);
$pdf->Cell(100, 6, utf8_decode('Método de Pago: ' . $factura['metodo_pago']), 0, 1); // Línea añadida
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
    $precio = $producto['precio_venta'];
    $iva = $producto['IVA'];
    $subtotal = $cantidad * $precio;
    $totalConIVA = $subtotal + ($subtotal * $iva / 100);
    $totalGeneral += $totalConIVA;

    $pdf->Cell(70, 8, utf8_decode($nombre), 1);
    $pdf->Cell(30, 8, utf8_decode($cantidad), 1);
    $pdf->Cell(30, 8, utf8_decode($iva . '%'), 1);
    $pdf->Cell(50, 8, utf8_decode(number_format($totalConIVA, 2)) . " " . chr(128), 1);
    $pdf->Ln();
}

// Total final
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(130, 8, 'Total Factura:', 1);
$pdf->Cell(50, 8, number_format($totalGeneral, 2) . " " . chr(128), 1);

$pdf->Output("I", "FacturaVenta_" . $factura['codigo'] . ".pdf");
?>
