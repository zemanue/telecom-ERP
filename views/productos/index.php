<!-- 
Este archivo contiene el HTML para mostrar la información principal de la sección de productos.
    - Incluye la tabla de productos, que se genera dinámicamente a partir de un array de productos.
    - También incluye el botón "Agregar Producto", que al hacer clic muestra el formulario para crear un nuevo producto.
    - El formulario de crear producto se encuentra en crear.php, que se incluye al hacer clic en el botón "Agregar Producto".
    - El formulario de editar producto se encuentra en editar.php, que se incluye al hacer clic en el botón "Editar" de la tabla.
    - Aunque no se vea aquí, incluye el header y el footer (que contienen la barra lateral y el menú de perfil desplegable).
-->

<!-- Incluir SweetAlert2 (CSS y JS) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content">
    <h1>Productos</h1>

    <!-- Botón para agregar producto -->
    <a href="../controllers/ProductoController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Producto
    </a>

    <!-- 📌 TABLA de productos -->
    <table class="table table-striped table-bordered" id="tablaProductos" data-table-id="tablaProductos">
        <!-- Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio compra</th>
                <th>Precio venta</th>
                <th>IVA</th>
                <th>Stock</th>
                <th>Código Proveedor</th>
                <th>Código Almacén</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!-- Cuerpo -->
        <tbody>
            <!-- Si el array tabla de productos no está vacío... -->
            <?php if (!empty($productos)): ?>
                <!-- Se recorre el array $productos (variable creada en ProductoController.php)
                    y genera una fila <tr> por cada producto. -->
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['codigo']; ?></td>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td><?php echo $producto['precio_compra']; ?></td>
                        <td><?php echo $producto['precio_venta']; ?></td>
                        <td><?php echo $producto['IVA']; ?></td>
                        <td><?php echo $producto['stock']; ?></td>
                        <td><?php echo $producto['codigo_proveedor']; ?></td>
                        <td><?php echo $producto['codigo_almacen']; ?></td>
                        <td class="acciones">
                            <!-- Botón de Editar con ícono -->
                            <a href="../controllers/ProductoController.php?action=edit&codigo=<?php echo $producto['codigo']; ?>"
                                class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-pen"></i> <!-- Ícono de lápiz para editar -->
                            </a>
    
                            <!-- Botón de Eliminar con SweetAlert2 -->
                            <a href="#"
                                class="btn btn-danger btn-sm btn-eliminar"
                                data-url="../controllers/ProductoController.php?action=delete&codigo=<?php echo $producto['codigo']; ?>"
                                title="Eliminar">
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

<!-- Script personalizado para eliminar con confirmación -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar');

    botonesEliminar.forEach(boton => {
        boton.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.dataset.url;

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará el producto permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
});
</script>
<!-- Agregar CSS de DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<!-- Agregar jQuery (DataTables depende de jQuery) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Agregar JS de DataTables -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- Inicializar DataTables -->
<script src="../assets/js/dataTables_init.js"></script>
