<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de empleado.
    - Incluye la tabla de empleado, que se genera dinámicamente a partir de un array de empleado.
    - También incluye el botón "Agregar Empleado", que al hacer clic muestra el formulario para crear un nuevo empleado.
    - El formulario de crear empleado se encuentra en crear.php, que se incluye al hacer clic en el botón "Agregar Empleado".
    - El formulario de editar empleado se encuentra en editar.php, que se incluye al hacer clic en el botón "Editar" de la tabla.
    - Aunque no se vea aquí, incluye el header y el footer (que contienen la barra lateral y el menú de perfil desplegable).

-->

<div class="content">
    <h1>Empleados</h1>

    <!-- Botón para agregar cliente -->
    <a href="../controllers/EmpleadosController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Empleado
    </a>

    <!-- 📌 TABLA de clientes -->
    <table class="table table-striped table-bordered" id="tablaEmpleados">
        <!--  Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Teléfono</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!--  Cuerpo -->
        <tbody>
            <!-- Si el array tabla de empleados no está vacía... -->
            <?php if (!empty($empleados)): ?>
                <!-- Se recorre el array $empleados (variable creada en EmpleadoController.php)
                    y genera una fila <tr> por cada empleado. -->
                <?php foreach ($empleados as $empleado): ?>
                    <tr>
                        <td><?php echo $empleado['codigo']; ?></td>
                        <td><?php echo $empleado['telefono']; ?></td>
                        <td><?php echo $empleado['nombre']; ?></td>
                        <td><?php echo $empleado['email']; ?></td>
                        <!-- La última celda de la fila contiene los botones de "Editar" y "Eliminar" -->
                        <td class="acciones">
                            <!-- Botón de Editar con ícono -->
                            <a href="../controllers/EmpleadoController.php?action=edit&codigo=<?php echo $empleado['codigo']; ?>"
                            class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-pen"></i> <!-- Ícono de lápiz para editar -->
                            </a>
    
                            <!-- Espacio entre los botones -->
                            <span>&nbsp;&nbsp;</span>
    
                            <!-- Botón de Eliminar con ícono -->
                            <a href="../controllers/EmpleadoController.php?action=delete&codigo=<?php echo $empleado['codigo']; ?>"
                            class="btn btn-danger btn-sm" title="Eliminar"
                            onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?')">
                                <i class="fas fa-trash"></i> <!-- Ícono de basurero para eliminar -->
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron empleado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>