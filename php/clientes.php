<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes | ERP</title>

    <!-- Bootstrap y FontAwesome -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Reemplazamos el script de Font Awesome con el enlace correcto -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script>
        function mostrarFormulario() {
            document.getElementById("tablaClientes").style.display = "none";
            document.getElementById("formularioCliente").style.display = "block";
        }

        function cancelarFormulario() {
            document.getElementById("formularioCliente").style.display = "none";
            document.getElementById("tablaClientes").style.display = "block";
        }
    </script>
</head>

<body>

    <!-- Barra lateral -->
    <div class="sidebar" style="width: 250px; background-color: #343a40; padding-top: 20px;">
        <h4 class="text-center text-white mb-4">ERP Gestión</h4>
        <a href="clientes.php" class="text-white"><i class="fas fa-users"></i> Clientes</a>
        <a href="proveedores.php" class="text-white"><i class="fas fa-truck"></i> Proveedores</a>
        <a href="empleados.php" class="text-white"><i class="fas fa-user-tie"></i> Empleados</a>
        <a href="productos.php" class="text-white"><i class="fas fa-box"></i> Productos</a>
        <a href="almacenes.php" class="text-white"><i class="fas fa-warehouse"></i> Almacenes</a>
        <a href="factura_compra.php" class="text-white"><i class="fas fa-file-invoice"></i> Factura de Compra</a>
        <a href="factura_venta.php" class="text-white"><i class="fas fa-shopping-cart"></i> Factura de Venta</a>
    </div>

    <!-- Menú de perfil desplegable -->
    <div class="profile-dropdown" style="position: absolute; top: 10px; right: 10px;">
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle p-0" type="button" id="dropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://st3.depositphotos.com/3433891/33504/i/450/depositphotos_335048212-stock-photo-young-caucasian-woman-isolated-who.jpg"
                    alt="Perfil" style="width: 40px; height: 40px; border-radius: 50%;">
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="ver_perfil.php"><i class="fas fa-id-badge"></i> Ver Perfil</a></li>
                <li><a class="dropdown-item" href="../index.html"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="content" style="margin-left: 270px; padding: 20px;">
        <h1>Clientes</h1>

        <!-- Botón para agregar cliente -->
        <button onclick="mostrarFormulario()" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Agregar Cliente
        </button>

        <!-- 📌 TABLA de clientes -->
        <div id="tablaClientes">
            <table class='table table-striped table-bordered'>
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
                <tbody>
                    <?php
                    include '..\config\conexion_be.php';
                    $sql = "SELECT * FROM cliente";
                    $resultado = mysqli_query($conexion, $sql);

                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>
                                <td>{$fila['codigo']}</td>
                                <td>{$fila['telefono']}</td>
                                <td>{$fila['nif']}</td>
                                <td>{$fila['nombre']}</td>
                                <td>{$fila['direccion']}</td>
                                <td>{$fila['poblacion']}</td>
                                <td>{$fila['email']}</td>
                                <td>{$fila['metodo_pago']}</td>
                                <td class='acciones'>
                                    <a href='clientes.php?accion=editar&codigo={$fila['codigo']}' class='btn btn-warning btn-sm'>
                                        <i class='fas fa-edit'></i> Editar
                                    </a>
                                    <a href='clientes.php?accion=eliminar&codigo={$fila['codigo']}' class='btn btn-danger btn-sm' 
                                    onclick='return confirm(\"¿Eliminar este cliente?\");'>
                                        <i class='fas fa-trash-alt'></i> Eliminar
                                    </a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- 📌 FORMULARIO Bootstrap (Oculto por defecto) -->
        <div id="formularioCliente" style="display: none;">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-plus"></i> Agregar Cliente</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="guardar_cliente.php">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono:</label>
                                <input type="text" class="form-control" name="telefono" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIF:</label>
                                <input type="text" class="form-control" name="nif" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre:</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dirección:</label>
                                <input type="text" class="form-control" name="direccion" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Población:</label>
                                <input type="text" class="form-control" name="poblacion" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Método de Pago:</label>
                                <select class="form-select" name="metodo_pago">
                                    <option value="Tarjeta">Tarjeta</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Efectivo">Efectivo</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar
                            Cliente</button>
                        <button type="button" onclick="cancelarFormulario()" class="btn btn-secondary"><i
                                class="fas fa-times"></i> Cancelar</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>

</html>