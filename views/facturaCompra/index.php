<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de proveedores.
    - Incluye la tabla de proveedores, que se genera dinámicamente a partir de un array de proveedores.
    - También incluye el botón "Agregar Proveedor", que al hacer clic muestra el formulario para crear un nuevo proveedor.
    - El formulario de crear proveedor se encuentra en crear.php, que se incluye al hacer clic en el botón "Agregar proveedor".
    - El formulario de editar proveedor se encuentra en editar.php, que se incluye al hacer clic en el botón "Editar" de la tabla.
    - Aunque no se vea aquí, incluye el header y el footer (que contienen la barra lateral y el menú de perfil desplegable).

-->

<div class="content">
    <h1>Proveedores</h1>

    <!-- Botón para agregar proveedor -->
    <a href="../controllers/FacturaCompraController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Proveedor
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
                        <!-- La última celda de la fila contiene los botones de "Editar" y "Eliminar" -->
                        <td class="acciones">
                            <!-- Botón de Editar con ícono -->
                            <a href="../controllers/FacturaCompraController.php?action=edit&codigo=<?php echo $factura['codigo']; ?>"
                            class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-pen"></i> <!-- Ícono de lápiz para editar -->
                            </a>
    
                            <!-- Espacio entre los botones -->
                            <span>&nbsp;&nbsp;</span>
    
                            <!-- Botón de Eliminar con ícono -->
                            <a href="../controllers/FacturaCompraController.php?action=delete&codigo=<?php echo $factura['codigo']; ?>"
                            class="btn btn-danger btn-sm" title="Eliminar"
                            onclick="return confirm('¿Estás seguro de que deseas eliminar este proveedor?')">
                                <i class="fas fa-trash"></i> <!-- Ícono de basurero para eliminar -->
                            </a>
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