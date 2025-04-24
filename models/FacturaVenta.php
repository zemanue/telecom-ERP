<?php
class FacturaVenta {
    private $db;

    // Constructor que recibe la conexión a la BD.
    public function __construct($db) {
        $this->db = $db;
    }

    // MÉTODOS PARA LAS REALIZAR LAS OPERACIONES CRUD
    // Obtener todas las facturas de ventas
    public function selectAll() {
        $stmt = $this->db->query("SELECT * FROM facturas_venta");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Métodos para crear, actualizar y eliminar facturas de ventas
    public function create($fecha, $direccion, $codigo_cliente, $codigo_empleado) {
        $stmt = $this->db->prepare(
            "INSERT INTO facturas_venta (fecha, direccion, codigo_cliente, codigo_empleado) 
            VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$fecha, $direccion, $codigo_cliente, $codigo_empleado]);
    }

    public function update($codigo, $fecha, $direccion, $codigo_cliente, $codigo_empleado) {
        $stmt = $this->db->prepare(
            "UPDATE facturas_venta
            SET fecha = ?, direccion = ?, codigo_cliente = ?, codigo_empleado = ?
            WHERE codigo = ?" 
        );
        return $stmt->execute([$fecha, $direccion, $codigo_cliente, $codigo_empleado, $codigo]);
    }

    public function delete($codigo) {
        $stmt = $this->db->prepare("DELETE FROM facturas_venta WHERE codigo = ?");
        return $stmt->execute([$codigo]);
    }

    public function getById($codigo) {
        $stmt = $this->db->prepare("SELECT * FROM facturas_venta WHERE codigo = ?");
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
