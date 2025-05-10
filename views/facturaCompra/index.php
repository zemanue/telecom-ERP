<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de Facturas de Compra.
    - Incluye el botón "Agregar Factura", que al hacer clic muestra el formulario para crear una nueva factura.
    - El formulario de editar proveedor se encuentra en editar.php, al que se va al hacer clic en el botón "Editar" de la tabla.
    - FacturaCompraController es la que incluye el header + esta página + el footer.
!-->
<div class="content">
    <h1>Factura Compra</h1>

    <!-- Botón para agregar proveedor -->
    <a href="../controllers/FacturaCompraController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Factura
    </a>

    <!-- 📌 TABLA de proveedores -->
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

        <!--  Cuerpo -->
        <tbody>
            <!-- Si el array tabla de proveedores no está vacía... -->
            <?php if (!empty($facturas)): ?>
                <!-- Se recorre el array $clientes (variable creada en ProveedorController.php)
                    y genera una fila <tr> por cada proveedor. -->
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
                                <!-- Botón de Editar !-->
                                <a href="../controllers/FacturaCompraController.php?action=edit&codigo=<?php echo $factura['codigo']; ?>"
                                    class="btn btn-warning btn-sm" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- Botón de Eliminar !-->
                                <a href="../controllers/FacturaCompraController.php?action=delete&codigo=<?php echo $factura['codigo']; ?>"
                                    class="btn btn-danger btn-sm" title="Eliminar"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar esta factura?')">
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
                    <td colspan="9">No se encontraron facturas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>