<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de proveedores.
    - Incluye la tabla de proveedores, que se genera dinámicamente a partir de un array de proveedores.
    - También incluye el botón "Agregar Proveedor", que al hacer clic muestra el formulario para crear un nuevo proveedor.
    - El formulario de crear proveedor se encuentra en crear.php, que se incluye al hacer clic en el botón "Agregar proveedor".
    - El formulario de editar proveedor se encuentra en editar.php, que se incluye al hacer clic en el botón "Editar" de la tabla.
    - Aunque no se vea aquí, incluye el header y el footer (que contienen la barra lateral y el menú de perfil desplegable).

-->

<div class="content">
    <h1>Proveedores</h1>

    <!-- Botón para agregar proveedor -->
    <a href="../controllers/ProveedorController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Proveedor
    </a>

    <!-- 📌 TABLA de proveedores -->
    <table class="table table-striped table-bordered" id="tablaProveedores">
        <!--  Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Teléfono</th>
                <th>NIF</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Población</th>
                <th>Email</th>
                <th>Deuda Existente</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!--  Cuerpo -->
        <tbody>
            <!-- Si el array tabla de proveedores no está vacía... -->
            <?php if (!empty($proveedores)): ?>
                <!-- Se recorre el array $clientes (variable creada en ProveedorController.php)
                    y genera una fila <tr> por cada proveedor. -->
                <?php foreach ($proveedores as $proveedor): ?>
                    <tr>
                        <td><?php echo $proveedor['codigo']; ?></td>
                        <td><?php echo $proveedor['telefono']; ?></td>
                        <td><?php echo $proveedor['nif']; ?></td>
                        <td><?php echo $proveedor['nombre']; ?></td>
                        <td><?php echo $proveedor['direccion']; ?></td>
                        <td><?php echo $proveedor['poblacion']; ?></td>
                        <td><?php echo $proveedor['email']; ?></td>
                        <td><?php echo $proveedor['deuda_existente']; ?></td>
                        <!-- La última celda de la fila contiene los botones de "Editar" y "Eliminar" -->
                        <!-- Estos botones apuntan a las secciones de actualizar y eliminar proveedores de ProveedorController.php  -->
                        <td>
                            <a href="../controllers/ProveedorController.php?action=edit&codigo=<?php echo $proveedor['codigo']; ?>"
                                class="btn btn-warning btn-sm">Editar</a>
                            <a href="../controllers/ProveedorController.php?action=delete&codigo=<?php echo $proveedor['codigo']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este proveedor?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron proveedores.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>