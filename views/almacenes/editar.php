
<!--
Este archivo contiene el formulario de editar almacenes
-->

<div class="container mt-4">
    <h2>Editar Almacén</h2>
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Almacén</h5>
        </div>
        <div class="card-body">

            <!-- Definimos un formulario de método POST para enviar a AlmacenController.php -->
            <form method="POST" action="../controllers/AlmacenController.php?action=edit">

                <!-- Campo oculto para la acción de edición -->
                <input type="hidden" name="action" value="edit">

                <!-- Campo oculto para el código del cliente -->
                <input type="hidden" name="codigo" value="<?php echo $almacen['codigo']; ?>">

                <!-- Campos del formulario -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre_almacen" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre_almacen" name="nombre_almacen" required>
                            value="<?php echo $almacen['nombre_almacen']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ubicacion" class="form-label">Ubicación:</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                            value="<?php echo $almacen['ubicacion']; ?>" required>
                    </div>
                </div>

                <!-- Botones para guardar o cancelar -->
                <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Guardar
                    Cambios</button>
                <a href="../controllers/AlmacenController.php?action=list" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </form>
        </div>
    </div>
</div>