<!--  
    Los archivos dentro de la carpeta models definen las clases para 
    cada una de las tablas de la base de datos.
    En este caso, la clase Almacen representa la tabla cliente de la base de datos.
    Cada una contiene la lógica para interactuar con la BD, es decir,
    los métodos para realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar).
-->

<?php
class Almacen {
    private $db;

    // Constructor que recibe la conexión a la BD. Esto permite que el controlador le pase la conexión al modelo.
    public function __construct($db) {
        $this->db = $db;
    }

    // MÉTODOS PARA LAS REALIZAR LAS OPERACIONES CRUD
    // Obtener todos los almacenes
    public function selectAll() {
        $stmt = $this->db->query("SELECT * FROM almacen"); // Selecciona todos los almacenes
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un array con todos los resultados
    }

    // Métodos para crear, actualizar y eliminar almacenes
    public function create($nombre, $ubicacion) {
        $stmt = $this->db->prepare(
            "INSERT INTO almacen (nombre_almacen, ubicacion) 
            VALUES (?, ?)"
        );
        return $stmt->execute([$nombre, $ubicacion]);
    }

    public function update($nombre, $ubicacion) {
        $stmt = $this->db->prepare(
            "UPDATE almacen 
            SET nombre_almacen = ?, ubicacion = ?
            WHERE codigo = ?"
        );
        return $stmt->execute([$nombre, $ubicacion, $codigo]);
    }

    public function delete($codigo) {
        $stmt = $this->db->prepare("DELETE FROM almacen WHERE codigo = ?");
        return $stmt->execute([$codigo]);
    }

    public function getById($codigo) {
        $stmt = $this->db->prepare("SELECT * FROM almacen WHERE codigo = ?");
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>