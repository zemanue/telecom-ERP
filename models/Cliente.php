<!--  
    Los archivos dentro de la carpeta models definen las clases para 
    cada una de las tablas de la base de datos.
    En este caso, la clase Cliente representa la tabla cliente de la base de datos.
    Cada una contiene la lógica para interactuar con la BD, es decir,
    los métodos para realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar).
-->

<?php
class Cliente {
    private $db;

    // Constructor que recibe la conexión a la BD. Esto permite que el controlador le pase la conexión al modelo.
    public function __construct($db) {
        $this->db = $db;
    }

    // MÉTODOS PARA LAS REALIZAR LAS OPERACIONES CRUD

    // Obtener todos los clientes
    public function selectAll() {
        // Ejecuta una consulta que selecciona todos los registros de la tabla cliente
        $stmt = $this->db->query("SELECT * FROM cliente"); 
        // Devuelve todos los resultados como un array asociativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo cliente en la base de datos
    public function create($telefono, $nif, $nombre, $direccion, $poblacion, $email, $metodo_pago) {
        // Preparamos la consulta con parámetros para evitar inyecciones SQL
        $stmt = $this->db->prepare(
            "INSERT INTO cliente (telefono, nif, nombre, direccion, poblacion, email, metodo_pago) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        // Ejecutamos la consulta con los valores proporcionados
        return $stmt->execute([$telefono, $nif, $nombre, $direccion, $poblacion, $email, $metodo_pago]);
    }

    // Actualizar un cliente existente
    public function update($codigo, $telefono, $nif, $nombre, $direccion, $poblacion, $email, $metodo_pago) {
        // Preparamos la consulta de actualización
        $stmt = $this->db->prepare(
            "UPDATE cliente 
            SET telefono = ?, nif = ?, nombre = ?, direccion = ?, poblacion = ?, email = ?, metodo_pago = ? 
            WHERE codigo = ?"
        );
        // Ejecutamos la consulta con los valores actualizados
        return $stmt->execute([$telefono, $nif, $nombre, $direccion, $poblacion, $email, $metodo_pago, $codigo]);
    }

    // Eliminar un cliente (con control de errores si tiene facturas asociadas)
    public function delete($codigo) {
        try {
            // Preparamos la consulta de eliminación
            $stmt = $this->db->prepare("DELETE FROM cliente WHERE codigo = ?");
            // Ejecutamos la eliminación
            return $stmt->execute([$codigo]);
        } catch (PDOException $e) {
            // Verificamos si el error es por clave foránea (cliente con facturas asociadas)
            if ($e->getCode() == 23000) {
                throw new Exception("No se puede eliminar el cliente porque tiene facturas asociadas.");
            } else {
                // Otro tipo de error
                throw $e;
            }
        }
    }

    // Obtener un cliente por su código
    public function getById($codigo) {
        // Preparamos la consulta para buscar un cliente específico
        $stmt = $this->db->prepare("SELECT * FROM cliente WHERE codigo = ?");
        $stmt->execute([$codigo]);
        // Retornamos un solo resultado como array asociativo
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
