<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de almacenes.
    - Incluye la tabla de almacenes, que se genera dinámicamente a partir de un array de almacenes.
    - También incluye el botón "Agregar Almacén", que al hacer clic muestra el formulario para crear un nuevo almacén.
    - El formulario de crear almacen se encuentra en crear.php, que se incluye al hacer clic en el botón "Agregar Almacén".
    - El formulario de editar almacén se encuentra en editar.php, que se incluye al hacer clic en el botón "Editar" de la tabla.
    - Aunque no se vea aquí, incluye el header y el footer (que contienen la barra lateral y el menú de perfil desplegable).

-->

<div class="content">
    <h1>Almacenes</h1>

    <!-- Botón para agregar almacén -->
    <a href="../controllers/AlmacenController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Almacén
    </a>

    <!-- 📌 TABLA de almacenes -->
    <table class="table table-striped table-bordered" id="tablaAlmacenes">
        <!-- Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!-- Cuerpo -->
        <tbody>
            <!-- Si el array tabla de almacenes no está vacío... -->
            <?php if (!empty($almacenes)): ?>
                <!-- Se recorre el array $almacenes (variable creada en AlmacenController.php)
                    y genera una fila <tr> por cada cliente. -->
                <?php foreach ($almacenes as $almacen): ?>
                    <tr>
                        <td><?php echo $almacen['codigo']; ?></td>
                        <td><?php echo $almacen['nombre_almacen']; ?></td>
                        <td><?php echo $almacen['ubicacion']; ?></td>
                        <!-- La última celda de la fila contiene los botones de "Editar" y "Eliminar" -->
                        <td class="acciones">
                            <!-- Botón de Editar con ícono -->
                            <a href="../controllers/AlmacenController.php?action=edit&codigo=<?php echo $almacen['codigo']; ?>"
                            class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-pen"></i> <!-- Ícono de lápiz para editar -->
                            </a>
    
                            <!-- Espacio entre los botones -->
                            <span>&nbsp;&nbsp;</span>
    
                            <!-- Botón de Eliminar con ícono -->
                            <a href="../controllers/AlmacenController.php?action=delete&codigo=<?php echo $almacen['codigo']; ?>"
                            class="btn btn-danger btn-sm" title="Eliminar"
                            onclick="return confirm('¿Estás seguro de que deseas eliminar este almacen?')">
                                <i class="fas fa-trash"></i> <!-- Ícono de basurero para eliminar -->
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron almacenes.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>