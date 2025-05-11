<!-- 
Este archivo contiene el HTML para mostrar la información principal de la sección de empleados.
    - Incluye la tabla de empleados, que se genera dinámicamente a partir de un array de empleados.
    - También incluye el botón "Agregar Empleado", que al hacer clic muestra el formulario para crear un nuevo empleado.
    - El formulario de crear empleado se encuentra en crear.php, que se incluye al hacer clic en el botón "Agregar Empleado".
    - El formulario de editar empleado se encuentra en editar.php, que se incluye al hacer clic en el botón "Editar" de la tabla.
    - Aunque no se vea aquí, incluye el header y el footer (que contienen la barra lateral y el menú de perfil desplegable).
-->

<!-- Incluir SweetAlert2 (CSS y JS) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content">
    <h1>Empleados</h1>

    <!-- Botón para agregar empleado -->
    <a href="../controllers/EmpleadosController.php?action=create" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Agregar Empleado
    </a>

    <!-- 📌 TABLA de empleados -->
    <table class="table table-striped table-bordered" id="tablaEmpleados">
        <!-- Encabezado -->
        <thead>
            <tr>
                <th>Código</th>
                <th>Teléfono</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!-- Cuerpo -->
        <tbody>
            <!-- Si el array tabla de empleados no está vacío... -->
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

                            <!-- Botón de Eliminar con SweetAlert2 -->
                            <a href="#"
                                class="btn btn-danger btn-sm btn-eliminar"
                                data-url="../controllers/EmpleadoController.php?action=delete&codigo=<?php echo $empleado['codigo']; ?>"
                                title="Eliminar">
                                <i class="fas fa-trash"></i> <!-- Ícono de basurero para eliminar -->
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron empleados.</td>
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
                text: "Esta acción eliminará el empleado permanentemente.",
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
