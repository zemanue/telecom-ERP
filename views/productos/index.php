<!--
Este archivo contiene el HTML para mostrar la información principal de la sección de productos.
    - Incluye la tabla de productos, que se genera dinámicamente a partir de un array de productos.
    - También incluye el botón "Agregar Producto", que al hacer clic muestra el formulario para crear un nuevo producto.
    - El formulario de crear producto se encuentra en crear.php, que se incluye al hacer clic en el botón "Agregar Producto".
    - El formulario de editar producto se encuentra en editar.php, que se incluye al hacer clic en el botón "Editar" de la tabla.
    - Aunque no se vea aquí, incluye el header y el footer (que contienen la barra lateral y el menú de perfil desplegable).

-->

<div class="content">
    <h1>Productos</h1>

    <!-- Botón para agregar producto -->
    <a href="../controllers/ProductoController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Producto
    </a>

    <!-- 📌 TABLA de producto -->
    <table class="table table-striped table-bordered" id="tablaProductos">
        <!--  Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio compra</th>
                <th>Precio venta</th>
                <th>IVA</th>
                <th>Código Proveedor</th>
                <th>Código Almacén</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!--  Cuerpo -->
        <tbody>
            <!-- Si el array tabla de productos no está vacía... -->
            <?php if (!empty($productos)): ?>
                <!-- Se recorre el array $productos (variable creada en ClienteController.php)
                    y genera una fila <tr> por cada cliente. -->
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['codigo']; ?></td>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td><?php echo $producto['precio_compra']; ?></td>
                        <td><?php echo $producto['precio_venta']; ?></td>
                        <td><?php echo $producto['IVA']; ?></td>
                        <td><?php echo $producto['codigo_proveedor']; ?></td>
                        <td><?php echo $producto['codigo_almacen']; ?></td>
                        <!-- La última celda de la fila contiene los botones de "Editar" y "Eliminar" -->
                        <td class="acciones">
                            <!-- Botón de Editar con ícono -->
                            <a href="../controllers/ProductoController.php?action=edit&codigo=<?php echo $producto['codigo']; ?>"
                            class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-pen"></i> <!-- Ícono de lápiz para editar -->
                            </a>
    
                            <!-- Espacio entre los botones -->
                            <span>&nbsp;&nbsp;</span>
    
                            <!-- Botón de Eliminar con ícono -->
                            <a href="../controllers/ProductoController.php?action=delete&codigo=<?php echo $producto['codigo']; ?>"
                            class="btn btn-danger btn-sm" title="Eliminar"
                            onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?')">
                                <i class="fas fa-trash"></i> <!-- Ícono de basurero para eliminar -->
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron productos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>