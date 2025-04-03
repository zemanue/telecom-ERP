
<!--
Este archivo contiene el formulario de editar proveedores
-->

<div class="container mt-4">
    <h2>Editar Proveedor</h2>
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Proveedor</h5>
        </div>
        <div class="card-body">

            <!-- Definimos un formulario de método POST para enviar a ProveedorController.php -->
            <form method="POST" action="../controllers/ProveedorController.php?action=edit">

                <!-- Campo oculto para la acción de edición -->
                <input type="hidden" name="action" value="edit">

                <!-- Campo oculto para el código del cliente -->
                <input type="hidden" name="codigo" value="<?php echo $proveedor['codigo']; ?>">

                <!-- Campos del formulario -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono:</label>
                        <input type="text" class="form-control" id="telefono" name="telefono"
                            value="<?php echo $proveedor['telefono']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nif" class="form-label">NIF:</label>
                        <input type="text" class="form-control" id="nif" name="nif"
                            value="<?php echo $proveedor['nif']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            value="<?php echo $proveedor['nombre']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="direccion" name="direccion"
                            value="<?php echo $proveedor['direccion']; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="poblacion" class="form-label">Población:</label>
                        <input type="text" class="form-control" id="poblacion" name="poblacion"
                            value="<?php echo $proveedor['poblacion']; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo $proveedor['email']; ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="deuda_existente" class="form-label">Deuda existente:</label>
                        <input type="decimal" class="form-control" id="deuda_existente" name="deuda_existente" value="<?php echo $proveedor['deuda_existente']; ?>" required>
                            
                    </div>
                </div>

                <!-- Botones para guardar o cancelar -->
                <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Guardar
                    Cambios</button>
                <a href="../controllers/ProveedorController.php?action=list" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </form>
        </div>
    </div>
</div>