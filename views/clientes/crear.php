
<!--
Este archivo contiene el formulario de crear clientes
-->

<div class="container mt-4">
    <h2>Agregar Cliente</h2>
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-plus"></i> Agregar Cliente</h5>
        </div>
        <div class="card-body">

            <!-- Definimos un formulario de método POST para enviar a ClienteController.php -->
            <form method="POST" action="../controllers/ClienteController.php?action=create">

                <!-- Campo oculto para la acción de creación -->
                <input type="hidden" name="action" value="create">

                <div class="row">

                    <!-- Campos del formulario -->
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono:</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nif" class="form-label">NIF:</label>
                        <input type="text" class="form-control" id="nif" name="nif" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="poblacion" class="form-label">Población:</label>
                        <input type="text" class="form-control" id="poblacion" name="poblacion" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago:</label>
                        <select class="form-select" id="metodo_pago" name="metodo_pago">
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="Efectivo">Efectivo</option>
                        </select>
                    </div>
                </div>

                <!-- Botones para guardar o cancelar -->
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cliente</button>
                <a href="../controllers/ClienteController.php?action=list" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </form>
        </div>
    </div>
</div>