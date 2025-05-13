<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de Facturas de Compra.
    - Incluye el botón "Agregar Factura", que al hacer clic muestra el formulario para crear una nueva factura.
    - El formulario de editar factura se encuentra en editar.php, al que se va al hacer clic en el botón "Editar" de la tabla.
    - FacturaCompraController es la que incluye el header + esta página + el footer.
-->

<!-- Incluir SweetAlert2 (CSS y JS) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content">
    <h1>Factura Compra</h1>

    <!-- Botón para agregar factura -->
    <a href="../controllers/FacturaCompraController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Factura
    </a>

    <!-- 📌 TABLA de facturas de compra -->
    <table class="table table-striped table-bordered" id="tablaFacturasCompra" data-table-id="tablaFacturasCompra">
        <!--  Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Dirección</th>
                <th>Empleado</th>
                <th>Método de pago</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!-- Cuerpo -->
        <tbody>
            <?php if (!empty($facturas)): ?>
                <?php foreach ($facturas as $factura): ?>
                    <tr>
                        <td><?php echo $factura['codigo']; ?></td>
                        <td><?php echo $factura['fecha']; ?></td>
                        <td><?php echo $factura['nombre_proveedor']; ?></td>
                        <td><?php echo $factura['direccion']; ?></td>
                        <td><?php echo $factura['nombre_empleado']; ?></td>
                        <td><?php echo $factura['metodo_pago']; ?></td>
                        <td><?php echo $factura['estado']; ?></td>
                        <td class="acciones">
                                <!-- Botón de Editar -->
                                <a href="../controllers/FacturaCompraController.php?action=edit&codigo=<?php echo $factura['codigo']; ?>"
                                    class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- Botón de Eliminar con SweetAlert2 -->
                            <a href="#"
                                class="btn btn-danger btn-sm btn-eliminar"
                                data-url="../controllers/FacturaCompraController.php?action=delete&codigo=<?php echo $factura['codigo']; ?>"
                                title="Eliminar">
                                <i class="fas fa-trash"></i> <!-- Ícono de basurero para eliminar -->
                            </a>

                                <!-- Botón de Descargar PDF -->
                                <a href="descargar_factura_compra.php?codigo=<?php echo $factura['codigo']; ?>"
                                    class="btn btn-primary btn-sm" title="Descargar PDF" target="_blank">
                                    <i class="fas fa-download"></i>
                                </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No se encontraron facturas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar');

    botonesEliminar.forEach(boton => {
        boton.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.dataset.url;

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará la factura permanentemente.",
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
