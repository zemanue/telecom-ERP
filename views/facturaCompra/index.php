<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de Facturas de Compra.
    - Incluye el botón "Agregar Factura", que al hacer clic muestra el formulario para crear una nueva factura.
    - El formulario de editar factura se encuentra en editar.php, al que se va al hacer clic en el botón "Editar" de la tabla.
    - FacturaCompraController es la que incluye el header + esta página + el footer.
-->

<!-- Incluir SweetAlert2 (CSS y JS) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content">
    <h1>Factura Compra</h1>

    <!-- Botón para agregar factura -->
    <a href="../controllers/FacturaCompraController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Factura
    </a>

    <!-- 📌 TABLA de facturas de compra -->
    <table class="table table-striped table-bordered" id="tablaFacturasCompra">
        <!--  Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Fecha</th>
                <th>Dirección</th>
                <th>Código proveedor</th>
                <th>Código empleado</th>
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
                        <td><?php echo $factura['direccion']; ?></td>
                        <td><?php echo $factura['codigo_proveedor']; ?></td>
                        <td><?php echo $factura['codigo_empleado']; ?></td>
                        <td><?php echo $factura['metodo_pago']; ?></td>
                        <td><?php echo $factura['estado']; ?></td>
                        <td class="acciones">
                            <div class="d-flex justify-content-center align-items-center gap-1 h-100">
                                <!-- Botón de Editar -->
                                <a href="../controllers/FacturaCompraController.php?action=edit&codigo=<?php echo $factura['codigo']; ?>"
                                   class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- Botón de Eliminar -->
                                <a href="#" class="btn btn-danger btn-sm btn-delete" 
                                   data-codigo="<?php echo $factura['codigo']; ?>" 
                                   title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>

                                <!-- Botón de Descargar PDF -->
                                <a href="descargar_factura_compra.php?codigo=<?php echo $factura['codigo']; ?>"
                                   class="btn btn-primary btn-sm" title="Descargar PDF" target="_blank">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
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
    // Configurar SweetAlert2 para la eliminación de factura
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Evitar que el enlace se ejecute inmediatamente

            const facturaCodigo = this.getAttribute('data-codigo');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres eliminar esta factura?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirigir a la URL de eliminación si el usuario confirma
                    window.location.href = `../controllers/FacturaCompraController.php?action=delete&codigo=${facturaCodigo}`;
                }
            });
        });
    });
</script>
