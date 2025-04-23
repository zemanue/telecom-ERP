<!-- 
Este archivo contiene la parte superior de todas las vistas de la aplicación, 
incluyendo la barra lateral de navegación y el menú de perfil desplegable.
-->
<?php
session_start(); // Iniciar sesión para acceder a las variables de sesión
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Gestión</title>
    <!-- Bootstrap y FontAwesome -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Reemplazamos el script de Font Awesome con el enlace correcto -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <!-- Barra lateral -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <div class="position-sticky pt-3">
                    <h4 class="text-center text-white mb-4">ERP Gestión</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../views/home.php">
                                <i class="fas fa-home"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../controllers/ClienteController.php?action=list">
                                <i class="fas fa-users"></i> Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../controllers/ProveedorController.php?action=list">
                                <i class="fas fa-truck"></i> Proveedores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../controllers/EmpleadoController.php?action=list">
                                <i class="fas fa-user-tie"></i> Empleados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../controllers/ProductoController.php?action=list">
                                <i class="fas fa-box"></i> Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../controllers/AlmacenController.php?action=list">
                                <i class="fas fa-warehouse"></i> Almacenes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../controllers/FacturaCompraController.php?action=list">
                                <i class="fas fa-file-invoice"></i> Factura de Compra
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../factura_venta.php?action=list">
                                <i class="fas fa-shopping-cart"></i> Factura de Venta
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

                    <!-- Menú de perfil desplegable -->
                    <div class="profile-dropdown">
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle p-0" type="button" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://st3.depositphotos.com/3433891/33504/i/450/depositphotos_335048212-stock-photo-young-caucasian-woman-isolated-who.jpg"
                                    alt="Perfil" style="width: 40px; height: 40px; border-radius: 50%;">
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="../perfil/ver_perfil.php"><i
                                            class="fas fa-id-badge"></i> Ver Perfil</a></li>
                                <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt"></i>
                                        Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    </div>
                </div>