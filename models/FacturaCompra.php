<!--  
    Los archivos dentro de la carpeta models definen las clases para 
    cada una de las tablas de la base de datos.
    En este caso, la clase Almacen representa la tabla cliente de la base de datos.
    Cada una contiene la lógica para interactuar con la BD, es decir,
    los métodos para realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar).
-->

<?php
require_once 'Producto.php'; 
class FacturaCompra {
    private $db;

    // Constructor que recibe la conexión a la BD. Esto permite que el controlador le pase la conexión al modelo.
    public function __construct($db) {
        $this->db = $db;
    }

    // MÉTODOS PARA LAS REALIZAR LAS OPERACIONES CRUD
    // Obtener todos los almacenes
    public function selectAll() {
        $stmt = $this->db->query("SELECT * FROM facturas_compra"); // Selecciona todos los almacenes
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un array con todos los resultados
    }

    // Nuevo método para obtener todas los detalles de las facturas + los nombres de proveedores y empleados
    public function selectAllWithDetails() {
        $sql = "SELECT
                    fc.codigo,
                    fc.fecha,
                    fc.direccion,
                    fc.codigo_proveedor,
                    p.nombre AS nombre_proveedor,
                    fc.codigo_empleado,
                    e.nombre AS nombre_empleado,
                    fc.metodo_pago,
                    fc.estado
                FROM
                    facturas_compra fc
                JOIN
                    proveedor p ON fc.codigo_proveedor = p.codigo
                JOIN
                    empleados e ON fc.codigo_empleado = e.codigo";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Métodos para crear, actualizar y eliminar almacenes
    public function create($fecha, $direccion, $codigo_proveedor, $codigo_empleado, $metodo_pago) {
        $sql = "INSERT INTO facturas_compra (fecha, direccion, codigo_proveedor, codigo_empleado, metodo_pago)
                VALUES (:fecha, :direccion, :codigo_proveedor, :codigo_empleado, :metodo_pago)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':codigo_proveedor', $codigo_proveedor);
        $stmt->bindParam(':codigo_empleado', $codigo_empleado);
        $stmt->bindParam(':metodo_pago', $metodo_pago);

        return $stmt->execute();
    }


    public function update($codigo, $fecha, $direccion, $codigo_proveedor, $codigo_empleado, $metodo_pago, $estado) {
        $stmt = $this->db->prepare(
            "UPDATE facturas_compra
            SET fecha = ?, direccion = ?, codigo_proveedor = ?, codigo_empleado = ?, metodo_pago = ?, estado = ?
            WHERE codigo = ?" 
        );
        return $stmt->execute([$fecha, $direccion, $codigo_proveedor, $codigo_empleado, $metodo_pago, $estado, $codigo]);
    }

    // Método para CAMBIAR EL ESTADO DE UNA FACTURA
    public function changeStatus($codigo, $estado) {
        $stmt = $this->db->prepare("UPDATE facturas_compra SET estado = ? WHERE codigo = ?");
        return $stmt->execute([$estado, $codigo]);
    }

    public function delete($codigo) {
        $stmt = $this->db->prepare("DELETE FROM facturas_compra WHERE codigo = ?");
        return $stmt->execute([$codigo]);
    }

    public function getById($codigo) {
        $stmt = $this->db->prepare("SELECT * FROM facturas_compra WHERE codigo = ?");
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}
?>