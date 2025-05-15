<!--
Este archivo contiene el formulario de crear facturas de ventas
-->

<div class="container mt-4">
    <h2>Agregar Factura de Venta</h2>
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-plus"></i> Agregar Factura de Venta</h5>
        </div>
        <div class="card-body">

            <!-- Definimos un formulario de método POST para enviar a FacturaVentaController.php -->
            <form method="POST" action="../controllers/FacturaVentaController.php?action=create">

                <!-- Campo oculto para la acción de creación -->
                <input type="hidden" name="action" value="create">

                <div class="row">

                    <!-- Campos del formulario -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha" class="form-label">Fecha:</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_cliente" class="form-label">Cliente:</label>
                        <select class="form-control" name="codigo_cliente" id="codigo_cliente" required>
                            <option value="">Seleccione un cliente</option>
                            <!-- Se generan las opciones del select por cada uno registrado en la base de datos -->
                            <?php if (!empty($clientes)): ?>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?php echo $cliente['codigo']; ?>">
                                        <?php echo $cliente['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No hay clientes registrados, debes registrar uno primero</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_empleado" class="form-label">Empleado:</label>
                        <select class="form-control" name="codigo_empleado" id="codigo_empleado" required>
                            <option value="">Seleccione un empleado</option>
                            <!-- Se generan las opciones del select por cada uno registrado en la base de datos -->
                            <?php if (!empty($empleados)): ?>
                                <?php foreach ($empleados as $empleado): ?>
                                    <option value="<?php echo $empleado['codigo']; ?>">
                                        <?php echo $empleado['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No hay empleados registrados, debes registrar uno primero</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago:</label>
                        <input list="metodos_pago" class="form-control" name="metodo_pago" id="metodo_pago" required>
                        <datalist id="metodos_pago">
                            <option value="Efectivo">
                            <option value="Tarjeta">
                            <option value="Transferencia">
                        </datalist>
                    </div>
                </div>

                <h5 class="mt-4">Productos incluidos</h5>
                <div id="producto-container">
                    <div class="producto-item row mb-2 align-items-center">
                        <div class="col-md-5">
                            <label>Producto</label>
                            <select name="productos[]" class="form-control producto-select" required>
                                <option value="">Seleccione un producto</option>
                                <?php if (!empty($productos)): ?>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?php echo $producto['codigo']; ?>" data-precio-venta="<?php echo $producto['precio_venta']; ?>">
                                        <?php echo $producto['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No hay productos registrados, debes registrar uno primero</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Cantidad</label>
                            <input type="number" name="cantidades[]" class="form-control cantidad-input" value="1" min="1" required>
                        </div>
                        <div class="col-md-2 text-end">
                            <span class="precio-linea">0.00 €</span>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm btn-remove">Quitar</button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="button" id="add-producto" class="btn btn-success mt-2">+ Añadir otro producto</button>
                </div>

                <div class="text-end me-5">
                    <h4>Precio Total: <span id="precio-total">0.00 €</span></h4>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Factura</button>
                <a href="../controllers/FacturaVentaController.php?action=list" class="btn btn-secondary"><i
                        class="fas fa-times"></i> Cancelar</a>
            </form>
        </div>
    </div>
</div>

<!-- Script para calcular el precio de venta de los productos -->
<script src="../assets/js/factura_venta.js"></script>
