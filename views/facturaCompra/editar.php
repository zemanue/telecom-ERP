
<!--
Este archivo contiene el formulario de editar proveedores
-->

<div class="container mt-4">
    <h2>Editar Factura</h2>
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Facturas</h5>
        </div>
        <div class="card-body">

            <!-- Definimos un formulario de método POST para enviar a ProveedorController.php -->
            <form method="POST" action="../controllers/FacturaCompraController.php?action=edit">

                <!-- Campo oculto para la acción de edición -->
                <input type="hidden" name="action" value="edit">

                <!-- Campo oculto para el código del cliente -->
                <input type="hidden" name="codigo" value="<?php echo $factura['codigo']; ?>">

                <!-- Campos del formulario -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha" class="form-label">fecha:</label>
                        <input type="text" class="form-control" id="fecha" name="fecha"
                            value="<?php echo $factura['fecha']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="direccion" name="direccion"
                            value="<?php echo $factura['direccion']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_proveedor" class="form-label">Código Proveedor:</label>
                        <input type="text" class="form-control" id="codigo_proveedor" name="codigo_proveedor" 
                        value="<?php echo $factura['codigo_proveedor']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_empleado" class="form-label">Código Empleado:</label>
                        <input type="text" class="form-control" id="codigo_empleado" name="codigo_empleado" 
                        value="<?php echo $factura['codigo_empleado']; ?>" required>
                    </div>
                </div>

                <h5 class="mt-4">Productos incluidos</h5>
                <div id="producto-container">
                    <?php foreach ($productos_factura as $detalle): ?>
                    <div class="producto-item row mb-2">
                    <div class="col-md-6">
                        <label>Producto</label>
                        <select name="productos[]" class="form-control" required>
                        <?php
                        $productos = $conexion->query("SELECT codigo, nombre FROM productos");
                        while ($producto = $productos->fetch_assoc()) {
                            $selected = $producto['codigo'] == $detalle['codigo_producto'] ? 'selected' : '';
                            echo "<option value='{$producto['codigo']}' $selected>{$producto['nombre']}</option>";
                        }
                        ?>
                        </select>
                    </div>
                <div class="col-md-3">
                    <label>Cantidad</label>
                    <input type="number" name="cantidades[]" class="form-control" value="<?= $detalle['cantidad'] ?>" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove">Quitar</button>
                </div>
                </div>
            <?php endforeach; ?>
            </div>
            <button type="button" id="add-producto" class="btn btn-success mt-2">+ Añadir otro producto</button>
            </form>
                <!-- Botones para guardar o cancelar -->
                <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Guardar
                    Cambios</button>
                <a href="../controllers/FacturaCompraController.php?action=list" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
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

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-remove')) {
        e.target.closest('.producto-item').remove();
    }
});
</script>