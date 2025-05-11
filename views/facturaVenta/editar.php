<!--
Este archivo contiene el formulario de editar facturas de ventas
-->

<div class="container mt-4">
    <h2>Editar Factura</h2>
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Facturas</h5>
        </div>
        <div class="card-body">

            <!-- Definimos un formulario de método POST para enviar a FacturaVentaController.php -->
            <form method="POST" action="../controllers/FacturaVentaController.php?action=edit">

                <!-- Campo oculto para la acción de edición -->
                <input type="hidden" name="action" value="edit">

                <!-- Campo oculto para el código de la factura -->
                <input type="hidden" name="codigo" value="<?php echo $factura['codigo']; ?>">

                <!-- Campos del formulario -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha" class="form-label">Fecha:</label>
                        <input type="date" class="form-control" id="fecha" name="fecha"
                            value="<?php echo $factura['fecha']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="direccion" name="direccion"
                            value="<?php echo $factura['direccion']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_cliente" class="form-label">Código Cliente:</label>
                        <select class="form-control" id="codigo_cliente" name="codigo_cliente" required>
                            <!-- Se genera una opción del select por cada cliente que hay en la base de datos -->
                            <?php foreach ($clientes as $cliente): ?>
                                <!-- El código de cliente que coincide con el de la factura se selecciona por defecto -->
                                <!-- Gracias al atributo "selected" -->
                                <option value="<?php echo $cliente['codigo']; ?>" <?php echo $cliente['codigo'] == $factura['codigo_cliente'] ? 'selected' : ''; ?>>
                                    <?php echo $cliente['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_empleado" class="form-label">Código Empleado:</label>
                        <select class="form-control" id="codigo_empleado" name="codigo_empleado" required>
                            <!-- Se genera una opción del select por cada empleado que hay en la base de datos -->
                            <?php foreach ($empleados as $empleado): ?>
                                <!-- El código de empleado que coincide con el de la factura se selecciona por defecto -->
                                <!-- Gracias al atributo "selected" -->
                                <option value="<?php echo $empleado['codigo']; ?>" <?php echo $empleado['codigo'] == $factura['codigo_empleado'] ? 'selected' : ''; ?>>
                                    <?php echo $empleado['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago:</label>
                        <select class="form-control" id="metodo_pago" name="metodo_pago" required>
                        <!-- El método de pago actual se selecciona por defecto -->
                            <option value="Efectivo" <?= $factura['metodo_pago'] == 'Efectivo' ? 'selected' : '' ?>>Efectivo</option>
                            <option value="Tarjeta" <?= $factura['metodo_pago'] == 'Tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
                            <option value="Transferencia" <?= $factura['metodo_pago'] == 'Transferencia' ? 'selected' : '' ?>>Transferencia</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado:</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="Borrador" <?= $factura['estado'] == 'Borrador' ? 'selected' : '' ?>>Borrador</option>
                            <option value="Emitida" <?= $factura['estado'] == 'Emitida' ? 'selected' : '' ?>>Emitida</option>
                            <option value="Pagada" <?= $factura['estado'] == 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                            <option value="Anulada" <?= $factura['estado'] == 'Anulada' ? 'selected' : '' ?>>Anulada</option>
                        </select>
                    </div>                
                </div>

                <h5 class="mt-4">Productos incluidos</h5>
                <div id="producto-container">
                    <?php foreach ($productos_factura as $detalle): ?>
                        <div class="producto-item row mb-2">
                            <div class="col-md-6">
                                <label>Producto</label>
                                <select name="productos[]" class="form-control" required>
                                    <!-- Se genera una opción del select por cada producto que hay en la base de datos -->
                                    <?php foreach ($productos as $producto): ?>
                                        <option value="<?php echo $producto['codigo']; ?>" <?php echo $producto['codigo'] == $detalle['codigo_producto'] ? 'selected' : ''; ?>>
                                            <?php echo $producto['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Cantidad</label>
                                <input type="number" name="cantidades[]" class="form-control"
                                    value="<?= $detalle['cantidad'] ?>" required>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-remove">Quitar</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="add-producto" class="btn btn-success mt-2">+ Añadir otro producto</button>
                <!-- Botones para guardar o cancelar -->
                <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Guardar
                    Cambios</button>
            </form>
            <a href="../controllers/FacturaVentaController.php?action=list" class="btn btn-secondary"><i
                    class="fas fa-times"></i> Cancelar</a>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-producto').addEventListener('click', () => {
        const container = document.getElementById('producto-container');
        const newItem = container.firstElementChild.cloneNode(true);
        newItem.querySelectorAll('input, select').forEach(el => el.value = '');
        container.appendChild(newItem);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove')) {
            e.target.closest('.producto-item').remove();
        }
    });
</script>
