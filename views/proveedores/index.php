<!-- 
Este archivo contiene el HTML para mostrar la información principal de la sección de proveedores.
    - Incluye la tabla de proveedores, que se genera dinámicamente a partir de un array de proveedores.
    - También incluye el botón "Agregar Proveedor", que al hacer clic muestra el formulario para crear un nuevo proveedor.
    - El formulario de crear proveedor se encuentra en crear.php, que se incluye al hacer clic en el botón "Agregar proveedor".
    - El formulario de editar proveedor se encuentra en editar.php, que se incluye al hacer clic en el botón "Editar" de la tabla.
    - Aunque no se vea aquí, incluye el header y el footer (que contienen la barra lateral y el menú de perfil desplegable).
-->

<!-- Incluir SweetAlert2 (CSS y JS) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content">
    <h1>Proveedores</h1>

    <!-- Botón para agregar proveedor -->
    <a href="../controllers/ProveedorController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Proveedor
    </a>

    <!-- 📌 TABLA de proveedores -->
    <table class="table table-striped table-bordered" id="tablaProveedores" data-table-id="tablaProveedores">
        <!-- Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>NIF</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Población</th>
                <th>Email</th>
                <th>Deuda Existente</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!-- Cuerpo -->
        <tbody>
            <!-- Si el array tabla de proveedores no está vacío... -->
            <?php if (!empty($proveedores)): ?>
                <!-- Se recorre el array $proveedores (variable creada en ProveedorController.php)
                    y genera una fila <tr> por cada proveedor. -->
                <?php foreach ($proveedores as $proveedor): ?>
                    <tr>
                        <td><?php echo $proveedor['codigo']; ?></td>
                        <td><?php echo $proveedor['nombre']; ?></td>
                        <td><?php echo $proveedor['nif']; ?></td>
                        <td><?php echo $proveedor['telefono']; ?></td>
                        <td><?php echo $proveedor['direccion']; ?></td>
                        <td><?php echo $proveedor['poblacion']; ?></td>
                        <td><?php echo $proveedor['email']; ?></td>
                        <td><?php echo $proveedor['deuda_existente']; ?></td>
                        <td class="acciones">
                            <!-- Botón de Editar con ícono -->
                            <a href="../controllers/ProveedorController.php?action=edit&codigo=<?php echo $proveedor['codigo']; ?>"
                                class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-pen"></i> <!-- Ícono de lápiz para editar -->
                            </a>



                            <!-- Botón de Eliminar con SweetAlert2 -->
                            <a href="#"
                                class="btn btn-danger btn-sm btn-eliminar"
                                data-url="../controllers/ProveedorController.php?action=delete&codigo=<?php echo $proveedor['codigo']; ?>"
                                title="Eliminar">
                                <i class="fas fa-trash"></i> <!-- Ícono de basurero para eliminar -->
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron proveedores.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Script personalizado para eliminar con confirmación -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar');

    botonesEliminar.forEach(boton => {
        boton.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.dataset.url;

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará el proveedor permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
});
</script>
<!-- Agregar CSS de DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<!-- Agregar jQuery (DataTables depende de jQuery) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Agregar JS de DataTables -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- Inicializar DataTables -->
<script src="../assets/js/dataTables_init.js"></script>
