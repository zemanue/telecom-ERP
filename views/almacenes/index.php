<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de almacenes.
-->

<!-- Incluir SweetAlert2 (CSS y JS) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content">
    <h1>Almacenes</h1>

    <!-- Botón para agregar almacén -->
    <a href="../controllers/AlmacenController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Almacén
    </a>

    <!-- 📌 TABLA de almacenes -->
    <table class="table table-striped table-bordered" id="tablaAlmacenes">
        <!-- Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!-- Cuerpo -->
        <tbody>
            <?php if (!empty($almacenes)): ?>
                <?php foreach ($almacenes as $almacen): ?>
                    <tr>
                        <td><?php echo $almacen['codigo']; ?></td>
                        <td><?php echo $almacen['nombre_almacen']; ?></td>
                        <td><?php echo $almacen['ubicacion']; ?></td>
                        <td class="acciones">
                            <!-- Botón de Editar -->
                            <a href="../controllers/AlmacenController.php?action=edit&codigo=<?php echo $almacen['codigo']; ?>"
                            class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-pen"></i>
                            </a>

                            <!-- Botón de Eliminar (con SweetAlert2) -->
                            <a href="#" 
                            class="btn btn-danger btn-sm btn-eliminar" 
                            data-url="../controllers/AlmacenController.php?action=delete&codigo=<?php echo $almacen['codigo']; ?>"
                            title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron almacenes.</td>
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
                text: "Esta acción eliminará el almacén permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',      // rojo
                cancelButtonColor: '#3085d6',    // azul fuerte
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminar',
                reverseButtons: true             // invierte el orden: "Cancelar" más visible
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

<script>
    $(document).ready(function() {
        // Inicializar DataTables en la tabla con el ID 'tablaAlumnos'
        $('#tablaAlmacenes').DataTable({
            "order": [[1, 'asc']], // Ordena por la columna "Nombre" por defecto (columna 1)
            "paging": true, // Habilita la paginación
            "searching": true, // Habilita la búsqueda en la tabla
            "lengthChange": true, // Habilita cambiar la cantidad de filas mostradas por página
            "language": { // Traducción al español
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sPrevious": "Anterior",
                    "sNext": "Siguiente",
                    "sLast": "Último"
                },
            }
        });
    });
</script>