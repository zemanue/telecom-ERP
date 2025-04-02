
<!--
Este archivo contiene el formulario de editar clientes
-->

<div class="container mt-4">
    <h2>Editar Cliente</h2>
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Cliente</h5>
        </div>
        <div class="card-body">
            <!-- Definimos un formulario de método POST para enviar a ClienteController.php -->
            <form method="POST" action="../controllers/ClienteController.php?action=edit">

                <!-- Campo oculto para el código del cliente -->
                <input type="hidden" name="codigo" value="<?php echo $cliente['codigo']; ?>">

                <!-- Campos del formulario -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono:</label>
                        <input type="text" class="form-control" id="telefono" name="telefono"
                            value="<?php echo $cliente['telefono']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nif" class="form-label">NIF:</label>
                        <input type="text" class="form-control" id="nif" name="nif"
                            value="<?php echo $cliente['nif']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            value="<?php echo $cliente['nombre']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="direccion" name="direccion"
                            value="<?php echo $cliente['direccion']; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="poblacion" class="form-label">Población:</label>
                        <input type="text" class="form-control" id="poblacion" name="poblacion"
                            value="<?php echo $cliente['poblacion']; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo $cliente['email']; ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago:</label>
                        <select class="form-select" id="metodo_pago" name="metodo_pago">
                            <option value="Tarjeta" <?php if ($cliente['metodo_pago'] === 'Tarjeta')
                                echo 'selected'; ?>>
                                Tarjeta</option>
                            <option value="Transferencia" <?php if ($cliente['metodo_pago'] === 'Transferencia')
                                echo 'selected'; ?>>Transferencia</option>
                            <option value="Efectivo" <?php if ($cliente['metodo_pago'] === 'Efectivo')
                                echo 'selected'; ?>>Efectivo</option>
                        </select>
                    </div>
                </div>

                <!-- Botones para guardar o cancelar -->
                <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Guardar
                    Cambios</button>
                <a href="../controllers/ClienteController.php?action=list" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </form>
        </div>
    </div>
</div>