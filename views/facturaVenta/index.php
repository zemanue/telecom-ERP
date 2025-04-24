<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de Facturas de Venta.
    - Incluye el botón "Agregar Factura", que al hacer clic muestra el formulario para crear una nueva factura.
    - El formulario de editar factura se encuentra en editar.php, al que se va al hacer clic en el botón "Editar" de la tabla.
    - FacturaVentaController es la que incluye el header + esta página + el footer.
-->

<div class="content">
    <h1>Factura Venta</h1>

    <!-- Botón para agregar factura -->
    <a href="../controllers/FacturaVentaController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Factura
    </a>

    <!-- 📌 TABLA de facturas -->
    <table class="table table-striped table-bordered" id="tablaFacturasVenta">
        <!--  Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Fecha</th>
                <th>Dirección</th>
                <th>Código Cliente</th>
                <th>Código Empleado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!--  Cuerpo -->
        <tbody>
            <!-- Si el array tabla de facturas no está vacía... -->
            <?php if (!empty($facturas)): ?>
                <!-- Se recorre el array $facturas (variable creada en FacturaVentaController.php)
                    y genera una fila <tr> por cada factura. -->
                <?php foreach ($facturas as $factura): ?>
                    <tr>
                        <td><?php echo $factura['codigo']; ?></td>
                        <td><?php echo $factura['fecha']; ?></td>
                        <td><?php echo $factura['direccion']; ?></td>
                        <td><?php echo $factura['codigo_cliente']; ?></td>
                        <td><?php echo $factura['codigo_empleado']; ?></td>
                        <!-- La última celda de la fila contiene los botones de "Editar" y "Eliminar" -->
                        <td class="acciones">
                            <!-- Botón de Editar con ícono -->
                            <a href="../controllers/FacturaVentaController.php?action=edit&codigo=<?php echo $factura['codigo']; ?>"
                            class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-pen"></i> <!-- Ícono de lápiz para editar -->
                            </a>
    
                            <!-- Espacio entre los botones -->
                            <span>&nbsp;&nbsp;</span>
    
                            <!-- Botón de Eliminar con ícono -->
                            <a href="../controllers/FacturaVentaController.php?action=delete&codigo=<?php echo $factura['codigo']; ?>"
                            class="btn btn-danger btn-sm" title="Eliminar"
                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta factura?')">
                                <i class="fas fa-trash"></i> <!-- Ícono de basurero para eliminar -->
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No se encontraron facturas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
