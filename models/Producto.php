<!--  
    Los archivos dentro de la carpeta models definen las clases para 
    cada una de las tablas de la base de datos.
    En este caso, la clase Producto representa la tabla cliente de la base de datos.
    Cada una contiene la lógica para interactuar con la BD, es decir,
    los métodos para realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar).
-->

<?php
class Producto {
    private $db;

    // Constructor que recibe la conexión a la BD. Esto permite que el controlador le pase la conexión al modelo.
    public function __construct($db) {
        $this->db = $db;
    }

    // MÉTODOS PARA LAS REALIZAR LAS OPERACIONES CRUD
    // Obtener todos los productos
    public function selectAll() {
        $stmt = $this->db->query("SELECT * FROM productos"); // Selecciona todos los productos
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un array con todos los resultados
    }

    // Nuevo método para obtener todos los detalles de los productos + los nombres de proveedores y almacenes
    public function selectAllWithDetails() {
        $sql = "SELECT
                    prod.codigo,
                    prod.nombre,
                    prod.precio_compra,
                    prod.precio_venta,
                    prod.IVA,
                    prod.codigo_proveedor,
                    prov.nombre AS nombre_proveedor,
                    prod.codigo_almacen,
                    alm.nombre_almacen AS nombre_almacen,
                    prod.stock
                FROM
                    productos prod
                JOIN
                    proveedor prov ON prod.codigo_proveedor = prov.codigo
                JOIN
                    almacen alm ON prod.codigo_almacen = alm.codigo";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Métodos para crear, actualizar y eliminar productos
    public function create($nombre, $precio_compra, $precio_venta, $iva, $stock, $codigo_proveedor, $codigo_almacen) {
        $stmt = $this->db->prepare(
            "INSERT INTO productos (nombre, precio_compra, precio_venta, IVA, stock, codigo_proveedor, codigo_almacen) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$nombre, $precio_compra, $precio_venta, $iva, $stock, $codigo_proveedor, $codigo_almacen]);
    }

    public function update($codigo, $nombre, $precio_compra, $precio_venta, $iva, $stock, $codigo_proveedor, $codigo_almacen) {
        $stmt = $this->db->prepare(
            "UPDATE productos  
            SET nombre = ?, precio_compra = ?, precio_venta = ?, IVA = ?, stock = ?, codigo_proveedor = ?, codigo_almacen = ? 
            WHERE codigo = ?"
        );
        return $stmt->execute([$nombre, $precio_compra, $precio_venta, $iva, $stock, $codigo_proveedor, $codigo_almacen, $codigo]);
    }

    public function delete($codigo) {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE codigo = ?");
        return $stmt->execute([$codigo]);
    }

    public function getById($codigo) {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE codigo = ?");
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Aumnetar stock (cuando se compra un producto)
    public function aumentarStock($codigo, $cantidad) {
        $stmt = $this->db->prepare("UPDATE productos SET stock = stock + ? WHERE codigo = ?");
        return $stmt->execute([$cantidad, $codigo]);
    }
    
    // Reducir stock (cuando se vende un producto)
    public function reducirStock($codigo, $cantidad) {
        // Validar que hay suficiente stock antes
        $producto = $this->getById($codigo);
        if ($producto && $producto['stock'] >= $cantidad) {
            $stmt = $this->db->prepare("UPDATE productos SET stock = stock - ? WHERE codigo = ?");
            return $stmt->execute([$cantidad, $codigo]);
        } else {
            return false; // No hay suficiente stock
        }
    }
}
?>