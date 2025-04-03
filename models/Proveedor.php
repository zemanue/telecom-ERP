<!--  
    Los archivos dentro de la carpeta models definen las clases para 
    cada una de las tablas de la base de datos.
    En este caso, la clase Proveedor representa la tabla cliente de la base de datos.
    Cada una contiene la lógica para interactuar con la BD, es decir,
    los métodos para realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar).
-->

<?php
class Proveedor {
    private $db;

    // Constructor que recibe la conexión a la BD. Esto permite que el controlador le pase la conexión al modelo.
    public function __construct($db) {
        $this->db = $db;
    }

    // MÉTODOS PARA LAS REALIZAR LAS OPERACIONES CRUD
    // Obtener todos los proveedores
    public function selectAll() {
        $stmt = $this->db->query("SELECT * FROM proveedor"); // Selecciona todos los proveedores
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un array con todos los resultados
    }

    // Métodos para crear, actualizar y eliminar clientes
    public function create($telefono, $nif, $nombre, $direccion, $poblacion, $email, $deuda_existente) {
        $stmt = $this->db->prepare(
            "INSERT INTO proveedor (telefono, nif, nombre, direccion, poblacion, email, deuda_existente) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$telefono, $nif, $nombre, $direccion, $poblacion, $email, $deuda_existente]);
    }

    public function update($codigo, $telefono, $nif, $nombre, $direccion, $poblacion, $email, $deuda_existente) {
        $stmt = $this->db->prepare(
            "UPDATE proveedor 
            SET telefono = ?, nif = ?, nombre = ?, direccion = ?, poblacion = ?, email = ?, deduda_existente = ? 
            WHERE codigo = ?"
        );
        return $stmt->execute([$telefono, $nif, $nombre, $direccion, $poblacion, $email, $deuda_existente, $codigo]);
    }

    public function delete($codigo) {
        $stmt = $this->db->prepare("DELETE FROM proveedor WHERE codigo = ?");
        return $stmt->execute([$codigo]);
    }

    public function getById($codigo) {
        $stmt = $this->db->prepare("SELECT * FROM proveedor WHERE codigo = ?");
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>