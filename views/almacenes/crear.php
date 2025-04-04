
<!--
Este archivo contiene el formulario de crear almacenes
-->

<div class="container mt-4">
    <h2>Agregar Almacén</h2>
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-plus"></i> Agregar Almacén</h5>
        </div>
        <div class="card-body">

            <!-- Definimos un formulario de método POST para enviar a AlmacenController.php -->
            <form method="POST" action="../controllers/AlmacenController.php?action=create">

                <!-- Campo oculto para la acción de creación -->
                <input type="hidden" name="action" value="create">

                <div class="row">

                    <!-- Campos del formulario -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre_almacen" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre_almacen" name="nombre_almacen" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ubicacion" class="form-label">Ubicación:</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                    </div>
                </div>

                <!-- Botones para guardar o cancelar -->
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Almacen</button>
                <a href="../controllers/AlamcenController.php?action=list" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </form>
        </div>
    </div>
</div>