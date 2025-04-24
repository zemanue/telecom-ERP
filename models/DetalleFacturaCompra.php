<?php
class DetalleFacturaCompra {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function insertarDetalle($codigo_factura, $codigo_producto, $cantidad) {
        $stmt = $this->db->prepare("INSERT INTO detalles_factura_compra (codigo_factura, codigo_producto, cantidad) VALUES (?, ?, ?)");
        return $stmt->execute([$codigo_factura, $codigo_producto, $cantidad]);
    }

    public function eliminarDetallesPorFactura($codigo_factura) {
        $stmt = $this->db->prepare("DELETE FROM detalles_factura_compra WHERE codigo_factura = ?");
        return $stmt->execute([$codigo_factura]);
    }

    public function obtenerDetallesPorFactura($codigo_factura) {
        $query = "SELECT * FROM detalles_factura_compra WHERE codigo_factura = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$codigo_factura]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>