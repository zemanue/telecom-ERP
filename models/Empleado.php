<!--  
    Los archivos dentro de la carpeta models definen las clases para 
    cada una de las tablas de la base de datos.
    En este caso, la clase Empleado representa la tabla empleado de la base de datos.
    Cada una contiene la lógica para interactuar con la BD, es decir,
    los métodos para realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar).
-->

<?php
class Empleado {
    private $db;

    // Constructor que recibe la conexión a la BD. Esto permite que el controlador le pase la conexión al modelo.
    public function __construct($db) {
        $this->db = $db;
    }

    // MÉTODOS PARA LAS REALIZAR LAS OPERACIONES CRUD
    // Obtener todos los empleados
    public function selectAll() {
        $stmt = $this->db->query("SELECT * FROM empleados"); // Selecciona todos los empleados
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un array con todos los resultados
    }

    // Métodos para crear, actualizar y eliminar clientes
    public function create($telefono, $nombre, $email) {
        $stmt = $this->db->prepare(
            "INSERT INTO empleados (telefono, nombre, email) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$telefono, $nombre, $email]);
    }

    public function update($codigo, $telefono, $nombre, $email) {
        $stmt = $this->db->prepare(
            "UPDATE empleados 
            SET telefono = ?, nombre = ?, email = ?
            WHERE codigo = ?"
        );
        return $stmt->execute([$telefono, $nombre, $email, $codigo]);
    }

    public function delete($codigo) {
        $stmt = $this->db->prepare("DELETE FROM empleados WHERE codigo = ?");
        return $stmt->execute([$codigo]);
    }

    public function getById($codigo) {
        $stmt = $this->db->prepare("SELECT * FROM empleados WHERE codigo = ?");
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>