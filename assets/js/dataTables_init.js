$(document).ready(function() {
    // Obtener el ID de la tabla del atributo data-table-id
    const tableId = $('table[data-table-id]').attr('data-table-id');

    if (tableId) {
        $(`#${tableId}`).DataTable({
            "order": [[1, 'asc']], // Orden por la segunda columna (index 1) por defecto (normalmente por el nombre)
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
            },
            "columnDefs": [
                { "orderable": false, "targets": -1 } // Deshabilita la ordenación para la última columna (la de acciones)
            ]
        });
    }
});