<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de clientes.
    - Incluye la tabla de clientes, que se genera dinámicamente a partir de un array de clientes.
    - También incluye el botón "Agregar Cliente", que al hacer clic muestra el formulario para crear un nuevo cliente.
    - El formulario de crear cliente se encuentra en crear.php, que se incluye al hacer clic en el botón "Agregar Cliente".
    - El formulario de editar cliente se encuentra en editar.php, que se incluye al hacer clic en el botón "Editar" de la tabla.
    - Aunque no se vea aquí, incluye el header y el footer (que contienen la barra lateral y el menú de perfil desplegable).

-->

<div class="content">
    <h1>Clientes</h1>

    <!-- Botón para agregar cliente -->
    <a href="../controllers/ClienteController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Cliente
    </a>

    <!-- 📌 TABLA de clientes -->
    <table class="table table-striped table-bordered" id="tablaClientes">
        <!-- Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Teléfono</th>
                <th>NIF</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Población</th>
                <th>Email</th>
                <th>Método de Pago</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!-- Cuerpo -->
        <tbody>
            <!-- Si el array tabla de clientes no está vacío... -->
            <?php if (!empty($clientes)): ?>
                <!-- Se recorre el array $clientes (variable creada en ClienteController.php)
                    y genera una fila <tr> por cada cliente. -->
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente['codigo']; ?></td>
                        <td><?php echo $cliente['telefono']; ?></td>
                        <td><?php echo $cliente['nif']; ?></td>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $cliente['direccion']; ?></td>
                        <td><?php echo $cliente['poblacion']; ?></td>
                        <td><?php echo $cliente['email']; ?></td>
                        <td><?php echo $cliente['metodo_pago']; ?></td>
                        <!-- La última celda de la fila contiene los botones de "Editar" y "Eliminar" -->
                        <td class="acciones">
                            <!-- Botón de Editar con ícono -->
                            <a href="../controllers/ClienteController.php?action=edit&codigo=<?php echo $cliente['codigo']; ?>"
                               class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-pen"></i> <!-- Ícono de lápiz para editar -->
                            </a>
    
                            <!-- Espacio entre los botones -->
                            <span>&nbsp;&nbsp;</span>
    
                            <!-- Botón de Eliminar con ícono -->
                            <a href="../controllers/ClienteController.php?action=delete&codigo=<?php echo $cliente['codigo']; ?>"
                               class="btn btn-danger btn-sm" title="Eliminar"
                               onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?')">
                                <i class="fas fa-trash"></i> <!-- Ícono de basurero para eliminar -->
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron clientes.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>