<!--
Este archivo contiene el formulario de crear productos
-->

<div class="container mt-4">
    <h2>Agregar Producto</h2>
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-plus"></i> Agregar Producto</h5>
        </div>
        <div class="card-body">

            <!-- Definimos un formulario de método POST para enviar a ProductoController.php -->
            <form method="POST" action="../controllers/ProductoController.php?action=create">

                <!-- Campo oculto para la acción de creación -->
                <input type="hidden" name="action" value="create">

                <!-- Campos del formulario -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="precio_compra" class="form-label">Precio Compra:</label>
                        <input type="number" class="form-control" id="precio_compra" name="precio_compra" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="precio_venta" class="form-label">Precio Venta:</label>
                        <input type="number" class="form-control" id="precio_venta" name="precio_venta" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="IVA" class="form-label">IVA:</label>
                        <input type="number" class="form-control" id="IVA" name="IVA" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_proveedor" class="form-label">Proveedor:</label>
                        <select class="form-control" name="codigo_proveedor" id="codigo_proveedor" required>
                            <option value="">Seleccione un proveedor</option>
                            <!-- Si la tabla de proveedores no está vacía, se generan las opciones del select por cada uno registrado en la base de datos -->
                            <?php if (!empty($proveedores)): ?>
                                <?php foreach ($proveedores as $proveedor): ?>
                                    <option value="<?php echo $proveedor['codigo']; ?>">
                                        <?php echo $proveedor['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No hay proveedores registrados, debes registrar uno primero</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_almacen" class="form-label">Almacén:</label>
                        <select class="form-control" name="codigo_almacen" id="codigo_almacen" required>
                            <option value="">Seleccione un almacén</option>
                            <?php if (!empty($almacenes)): ?>
                                <?php foreach ($almacenes as $almacen): ?>
                                    <option value="<?php echo $almacen['codigo']; ?>">
                                        <?php echo $almacen['nombre_almacen']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No hay almacenes registrados, debes registrar uno primero</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Botones para guardar o cancelar -->
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Producto</button>
                <a href="../controllers/ProductoController.php?action=list" class="btn btn-secondary"><i
                        class="fas fa-times"></i> Cancelar</a>
            </form>
        </div>
    </div>
</div>