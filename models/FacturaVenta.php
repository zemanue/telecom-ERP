<?php
class FacturaVenta {
    private $db;

    // Constructor que recibe la conexión a la BD.
    public function __construct($db) {
        $this->db = $db;
    }

    // MÉTODO GETTER PARA OBTENER LA CONEXIÓN DESDE EL CONTROLADOR
    public function getDb() {
        return $this->db;
    }

    // MÉTODOS PARA LAS REALIZAR LAS OPERACIONES CRUD

    // Obtener todas las facturas de ventas
    public function selectAll() {
        $stmt = $this->db->query("SELECT * FROM facturas_venta");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Nuevo método para obtener todas los detalles de las facturas + los nombres de clientes y empleados
    public function selectAllWithDetails() {
        $sql = "SELECT
                    fv.codigo,
                    fv.fecha,
                    fv.direccion,
                    fv.codigo_cliente,
                    c.nombre AS nombre_cliente,
                    fv.codigo_empleado,
                    e.nombre AS nombre_empleado,
                    fv.metodo_pago,
                    fv.estado
                FROM
                    facturas_venta fv
                JOIN
                    cliente c ON fv.codigo_cliente = c.codigo
                JOIN
                    empleados e ON fv.codigo_empleado = e.codigo";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // MÉTODO PARA CREAR UNA NUEVA FACTURA DE VENTA
public function create($fecha, $direccion, $codigo_cliente, $codigo_empleado, $metodo_pago) {
    $stmt = $this->db->prepare(
        "INSERT INTO facturas_venta (fecha, direccion, codigo_cliente, codigo_empleado, metodo_pago)
        VALUES (:fecha, :direccion, :codigo_cliente, :codigo_empleado, :metodo_pago)"
    );
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':codigo_cliente', $codigo_cliente);
    $stmt->bindParam(':codigo_empleado', $codigo_empleado);
    $stmt->bindParam(':metodo_pago', $metodo_pago);
    $stmt->execute();
    return $this->db->lastInsertId(); // Devolver el ID de la nueva factura
}

    // MÉTODO PARA ACTUALIZAR UNA FACTURA DE VENTA
    public function update($codigo, $fecha, $direccion, $codigo_cliente, $codigo_empleado, $metodo_pago, $estado) {
        // Preparamos la consulta SQL para actualizar la factura
        $stmt = $this->db->prepare(
            "UPDATE facturas_venta
            SET fecha = ?, direccion = ?, codigo_cliente = ?, codigo_empleado = ?, metodo_pago = ?, estado = ?
            WHERE codigo = ?"
        );
        // Ejecutamos la consulta y devolvemos si fue exitosa
        return $stmt->execute([$fecha, $direccion, $codigo_cliente, $codigo_empleado, $metodo_pago, $estado, $codigo]);
    }

    // MÉTODO PARA ELIMINAR UNA FACTURA DE VENTA
    public function delete($codigo) {
        // Preparamos la consulta para eliminar la factura
        $stmt = $this->db->prepare("DELETE FROM facturas_venta WHERE codigo = ?");
        // Ejecutamos la consulta y devolvemos si fue exitosa
        return $stmt->execute([$codigo]);
    }

    // MÉTODO PARA OBTENER UNA FACTURA POR SU CÓDIGO
    public function getById($codigo) {
        // Preparamos la consulta para obtener los datos de una factura específica
        $stmt = $this->db->prepare("SELECT * FROM facturas_venta WHERE codigo = ?");
        $stmt->execute([$codigo]);
        // Retornamos los resultados como un array asociativo
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
