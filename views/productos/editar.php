
<!--
Este archivo contiene el formulario de editar productos
-->

<div class="container mt-4">
    <h2>Editar Producto</h2>
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Producto</h5>
        </div>
        <div class="card-body">

            <!-- Definimos un formulario de método POST para enviar a ProductoController.php -->
            <form method="POST" action="../controllers/ProductoController.php?action=edit">

                <!-- Campo oculto para la acción de edición -->
                <input type="hidden" name="action" value="edit">

                <!-- Campo oculto para el código del cliente -->
                <input type="hidden" name="codigo" value="<?php echo $producto['codigo']; ?>">

                <!-- Campos del formulario -->
                <div class="row">
                <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"  
                        value="<?php echo $producto['nombre']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="precio_compra" class="form-label">Precio Compra:</label>
                        <input type="number" class="form-control" id="precio_compra" name="precio_compra" 
                        value="<?php echo $producto['precio_compra']; ?>" required>    
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="precio_venta" class="form-label">Precio Venta:</label>
                        <input type="number" class="form-control" id="precio_venta" name="precio_venta" 
                        value="<?php echo $producto['precio_venta']; ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="IVA" class="form-label">IVA:</label>
                        <input type="number" class="form-control" id="IVA" name="IVA" 
                        value="<?php echo $producto['IVA']; ?>"required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="codigo_proveedor" class="form-label">Código Proveedor:</label>
                        <select class="form-control" id="codigo_proveedor" name="codigo_proveedor" required>
                            <!-- Se genera una opción del select por cada proveedor que hay en la base de datos -->
                            <?php foreach ($proveedores as $proveedor): ?>
                                <!-- El código de proveedor que coincide con el del producto se selecciona por defecto -->
                                <!-- Gracias al atributo "selected" -->
                                <option value="<?php echo $proveedor['codigo']; ?>" 
                                    <?php echo $proveedor['codigo'] == $producto['codigo_proveedor'] ? 'selected' : ''; ?>> 
                                    <?php echo $proveedor['nombre']; ?>
                                </option> 
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="codigo_almacen" class="form-label">Código Almacén:</label>
                        <select class="form-control" id="codigo_almacen" name="codigo_almacen" required>
                            <!-- Ocurre lo mismo que con los proveedores más arriba :) -->
                            <?php foreach ($almacenes as $almacen): ?>
                                <option value="<?php echo $almacen['codigo']; ?>" 
                                    <?php echo $almacen['codigo'] == $producto['codigo_almacen'] ? 'selected' : ''; ?>> 
                                    <?php echo $almacen['nombre_almacen']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Botones para guardar o cancelar -->
                <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Guardar
                    Cambios</button>
                <a href="../controllers/ProductoController.php?action=list" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </form>
        </div>
    </div>
</div>