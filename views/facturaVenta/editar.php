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

            <!-- Formulario POST para cambiar el estado de la factura -->
            <!-- Independiente del formulario principal para poder cambiar el estado de la factura si no se puede editar -->
            <form method="POST" action="../controllers/FacturaVentaController.php">
                <input type="hidden" name="action" value="change_status">
                <input type="hidden" name="codigo" value="<?php echo $factura['codigo']; ?>">
                <div class="mb-3 mt-3">
                    <h5 class="mt-4">
                        <label for="estado" class="form-label">Cambiar estado de la factura</label>
                    </h5>
                    <select class="form-control" id="estado" name="estado" required>
                        <option value="Borrador" <?= $factura['estado'] == 'Borrador' ? 'selected' : '' ?>>Borrador</option>
                        <option value="Emitida" <?= $factura['estado'] == 'Emitida' ? 'selected' : '' ?>>Emitida</option>
                        <option value="Pagada" <?= $factura['estado'] == 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                        <option value="Anulada" <?= $factura['estado'] == 'Anulada' ? 'selected' : '' ?>>Anulada</option>
                    </select>
                    
                </div>
                <button type="submit" class="btn btn-info">
                    <i class="fas fa-sync-alt"></i> Cambiar Estado
                </button>
            </form>
            <hr>

            <?php if ($factura['estado'] !== 'Borrador'): ?>
                <div class="container mt-5">
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Factura no editable</h4>
                        <p>No se puede editar la factura porque tiene un estado distinto a "Borrador".</p>
                        <p>Estado actual: <?php echo $factura['estado']; ?></p>
                        <p>Prueba a cambiar el estado a "Borrador" y vuelve a intentarlo.</p>
                        <hr>
                        <a href="javascript:history.back()" class="btn btn-outline-danger">Volver atrás</a>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Formulario POST para editar la factura -->
            <!-- Este formulario tendrá los campos desactivados si la factura no está en estado "Borrador" -->
            <!-- Se utiliza la línea "if ($factura['estado'] !== 'Borrador') echo 'disabled';" para desactivar cada campo -->
            <h5 class="mt-4">Detalles de la factura</h5>
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
                            value="<?php echo $factura['fecha']; ?>" required
                            <?php if ($factura['estado'] !== 'Borrador') echo 'disabled'; ?>>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="direccion" name="direccion"
                            value="<?php echo $factura['direccion']; ?>" required
                            <?php if ($factura['estado'] !== 'Borrador') echo 'disabled'; ?>>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_cliente" class="form-label">Código Cliente:</label>
                        <select class="form-control" id="codigo_cliente" name="codigo_cliente" required
                        <?php if ($factura['estado'] !== 'Borrador') echo 'disabled'; ?>>
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
                        <select class="form-control" id="codigo_empleado" name="codigo_empleado" required
                        <?php if ($factura['estado'] !== 'Borrador') echo 'disabled'; ?>>
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
                        <select class="form-control" id="metodo_pago" name="metodo_pago" required
                        <?php if ($factura['estado'] !== 'Borrador') echo 'disabled'; ?>>
                        <!-- El método de pago actual se selecciona por defecto -->
                            <option value="Efectivo" <?= $factura['metodo_pago'] == 'Efectivo' ? 'selected' : '' ?>>Efectivo</option>
                            <option value="Tarjeta" <?= $factura['metodo_pago'] == 'Tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
                            <option value="Transferencia" <?= $factura['metodo_pago'] == 'Transferencia' ? 'selected' : '' ?>>Transferencia</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado:</label>
                        <select class="form-control" id="estado" name="estado" required
                        <?php if ($factura['estado'] !== 'Borrador') echo 'disabled'; ?>>
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
                            <div class="producto-item row mb-2 align-items-center">
                                <div class="col-md-5">
                                    <label>Producto</label>
                                    <select name="productos[]" class="form-control producto-select" required
                                        <?php if ($factura['estado'] !== 'Borrador') echo 'disabled'; ?>>
                                        <option value="">Seleccione un producto</option>
                                        <?php foreach ($productos as $producto): ?>
                                            <option value="<?php echo $producto['codigo']; ?>"
                                                data-precio-venta="<?php echo $producto['precio_venta']; ?>"
                                                <?php echo $producto['codigo'] == $detalle['codigo_producto'] ? 'selected' : ''; ?>>
                                                <?php echo $producto['nombre']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Cantidad</label>
                                    <input type="number" name="cantidades[]" class="form-control cantidad-input"
                                        value="<?= $detalle['cantidad'] ?>" min="1" required
                                        <?php if ($factura['estado'] !== 'Borrador') echo 'disabled'; ?>>
                                </div>
                                <div class="col-md-2 text-end">
                                    <span class="precio-linea">0.00 €</span>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove"
                                        <?php if ($factura['estado'] !== 'Borrador') echo 'disabled'; ?>>Quitar</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mb-3">
                        <button type="button" id="add-producto" class="btn btn-success mt-2"
                            <?php if ($factura['estado'] !== 'Borrador') echo 'disabled'; ?>>+ Añadir otro producto</button>
                    </div>

                    <div class="text-end me-5">
                        <h4>Precio Total: <span id="precio-total">0.00 €</span></h4>
                    </div>

                    <button type="submit" name="update" class="btn btn-primary"
                        <?php if ($factura['estado'] !== 'Borrador') echo 'disabled'; ?>>
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </form>
                <a href="../controllers/FacturaVentaController.php?action=list" class="btn btn-secondary"><i
                        class="fas fa-times"></i> Cancelar</a>
        </div>
    </div>
</div>

<!-- Script para calcular el precio de venta de los productos -->
<script src="../assets/js/factura_venta.js"></script>