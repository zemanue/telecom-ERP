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

    // MÉTODO PARA CREAR UNA NUEVA FACTURA DE VENTA
    // Este método también inserta los detalles y reduce el stock
    public function create($fecha, $direccion, $codigo_cliente, $codigo_empleado, $detalles) {
        try {
            // Iniciamos una transacción
            $this->db->beginTransaction();

            // Insertar la factura principal
            $stmt = $this->db->prepare(
                "INSERT INTO facturas_venta (fecha, direccion, codigo_cliente, codigo_empleado) 
                VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$fecha, $direccion, $codigo_cliente, $codigo_empleado]);

            // Obtener el ID (código) de la nueva factura creada
            $codigo_factura = $this->db->lastInsertId();

            // Insertar cada producto en los detalles de la factura
            foreach ($detalles as $detalle) {
                $producto_id = $detalle['producto_id'];
                $cantidad = $detalle['cantidad'];

                // Insertar el detalle en la tabla detalles_factura_venta
                $stmtDetalle = $this->db->prepare(
                    "INSERT INTO detalles_factura_venta (codigo_factura, codigo_producto, cantidad) 
                    VALUES (?, ?, ?)"
                );
                $stmtDetalle->execute([$codigo_factura, $producto_id, $cantidad]);

                // Reducir el stock del producto en la tabla productos
                $stmtStock = $this->db->prepare(
                    "UPDATE productos SET stock = stock - ? WHERE codigo = ?"
                );
                $stmtStock->execute([$cantidad, $producto_id]);
            }

            // Confirmamos la transacción si todo fue exitoso
            $this->db->commit();
            return true;

        } catch (Exception $e) {
            // Rollback si hay un error en cualquier parte
            $this->db->rollBack();
            error_log("Error al crear la factura de venta: " . $e->getMessage());
            return false;
        }
    }

    // MÉTODO PARA ACTUALIZAR UNA FACTURA DE VENTA
    public function update($codigo, $fecha, $direccion, $codigo_cliente, $codigo_empleado) {
        // Preparamos la consulta SQL para actualizar la factura
        $stmt = $this->db->prepare(
            "UPDATE facturas_venta
            SET fecha = ?, direccion = ?, codigo_cliente = ?, codigo_empleado = ? 
            WHERE codigo = ?"
        );
        // Ejecutamos la consulta y devolvemos si fue exitosa
        return $stmt->execute([$fecha, $direccion, $codigo_cliente, $codigo_empleado, $codigo]);
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
