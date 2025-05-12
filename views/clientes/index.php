<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de clientes.
-->

<!-- Incluir SweetAlert2 (CSS y JS) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content">
    <h1>Clientes</h1>

    <!-- Botón para agregar cliente -->
    <a href="../controllers/ClienteController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Cliente
    </a>

    <!-- 📌 TABLA de clientes -->
    <table class="table table-striped table-bordered" id="tablaClientes">
        <thead>
            <tr>
                <th>Código</th>
                <th>Teléfono</th>
                <th>NIF</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Población</th>
                <th>Email</th>
                <th>Método de Pago</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clientes)): ?>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente['codigo']; ?></td>
                        <td><?php echo $cliente['telefono']; ?></td>
                        <td><?php echo $cliente['nif']; ?></td>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $cliente['direccion']; ?></td>
                        <td><?php echo $cliente['poblacion']; ?></td>
                        <td><?php echo $cliente['email']; ?></td>
                        <td><?php echo $cliente['metodo_pago']; ?></td>
                        <td class="acciones">
                            <!-- Botón de Editar -->
                            <a href="../controllers/ClienteController.php?action=edit&codigo=<?php echo $cliente['codigo']; ?>"
                                class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-pen"></i>
                            </a>                           

                            <!-- Botón de Eliminar con SweetAlert2 -->
                            <a href="#"
                                class="btn btn-danger btn-sm btn-eliminar"
                                data-url="../controllers/ClienteController.php?action=delete&codigo=<?php echo $cliente['codigo']; ?>"
                                title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron clientes.</td>
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
                text: "Esta acción eliminará el cliente permanentemente.",
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

<script>
    $(document).ready(function() {
        // Inicializar DataTables en la tabla con el ID 'tablaAlumnos'
        $('#tablaClientes').DataTable({
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
