
# Telecom ERP

<div align="center">
    <img src="https://img.shields.io/badge/HTML-E34F26?style=flat&logo=html5&logoColor=white" alt="HTML">
    <img src="https://img.shields.io/badge/CSS-1572B6?style=flat&logo=css3&logoColor=white" alt="CSS">
    <img src="https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white" alt="PHP">
    <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black" alt="JavaScript">
    <img src="https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white" alt="MySQL">
    <img src="https://img.shields.io/badge/Status-In_Progress-yellow" alt="Status: In Progress">
</div>


![image](https://github.com/user-attachments/assets/d441bfc3-1f3b-4f78-887e-aa85d06adde8)

![image](https://github.com/user-attachments/assets/97d04e6e-c9eb-41a4-9507-19982a470301)

![image](https://github.com/user-attachments/assets/1eacb6c5-0fc5-4692-8af7-2c643a486f26)

![image](https://github.com/user-attachments/assets/0918c604-2fd5-466d-9711-15a0fa24dd98)


[English version ⬇️](#description)

## Descripción
Telecom ERP es una aplicación basada en web diseñada para gestionar varios aspectos de un negocio ficticio, incluyendo clientes, proveedores, empleados, productos y facturas.
Proporciona una interfaz fácil de usar para realizar operaciones CRUD y gestionar flujos de trabajo empresariales de manera eficiente.

Desarrollado por [Avalob](https://github.com/Avalob), [nattfer](https://github.com/nattfer), [ATreCro](https://github.com/ATreCro) y [@zemanue](https://github.com/zemanue).

## Características
- Autenticación de usuario (inicio de sesión y registro).
- Gestión de clientes, proveedores, empleados, almacenes y productos.
- Creación, edición y eliminación de facturas para compras y ventas.
- Validación para garantizar la integridad de los datos (por ejemplo, no productos sin proveedores o almacenes).
- Diseño responsivo utilizando Bootstrap.
- Organizado siguiendo el patrón de diseño Modelo-Vista-Controlador.

## Tecnologías empleadas
- **Backend**: PHP, JavaScript
- **Frontend**: HTML, CSS, JavaScript, Bootstrap
- **Base de datos**: MySQL
- **Otras herramientas**: XAMPP (para la configuración del servidor local)

## Instrucciones de Instalación
1. Clona el repositorio:
   ```git clone https://github.com/your-repo/telecom-erp.git```
2. Configura un servidor local usando XAMPP o similar.
3. Coloca la carpeta del proyecto en el directorio `htdocs` de XAMPP.
4. Inicia Apache y MySQL desde el panel de control de XAMPP.
5. Importa la base de datos:
    -Abre `phpMyAdmin` en tu navedagor.
    - Crea una nueva base de datos llamada `telecom`.
    - Importa el archivo SQL ubicado en `assets/sql/crear_tablas.sql.`
6. Actualiza la configuración de conexión de la base de datos en config/database.php:
```
<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "telecom";
```

## Uso
1. Abre la aplicación en tu navegador:
```http://localhost/telecom/index.php```
2. Registra un nuevo usuario o inicia sesión con una cuenta existente.
3. Navega por el menú para gestionar clientes, proveedores, empleados, productos y facturas.

## Contribución
¡Las contribuciones son bienvenidas! Por favor, sigue estos pasos:
1. Haz un fork del repositorio.
2. Crea una nueva rama para tu característica o corrección de errores:
```git checkout -b feature-name```
3. Confirma tus cambios y envíalos a tu fork.
4. Envía una solicitud de extracción con una descripción detallada de tus cambios.

## Contacto
Para preguntas o soporte, por favor contacta a:
- [@zemanue](https://github.com/zemanue)
- [@ATreCro](https://github.com/ATreCro)
- [@nattfer](https://github.com/nattfer)
- [@Avalob](https://github.com/Avalob)



[Versión en español ⬆️](#descripción)

## Description
Telecom ERP is a web-based application designed to manage various aspects of a fictional business, including clients, providers, employees, products, and invoices.
It provides a user-friendly interface for performing CRUD operations and managing business workflows efficiently.

Developed by [Avalob](https://github.com/Avalob), [nattfer](https://github.com/nattfer), [ATreCro](https://github.com/ATreCro) and [@zemanue](https://github.com/zemanue).

## Features
- User authentication (login and registration).
- Manage clients, providers, employees, warehouses and products.
- Create, edit, and delete invoices for purchases and sales.
- Validation to ensure data integrity (e.g., no products without providers or warehouses).
- Responsive design using Bootstrap.
- Organized following the Model_View-Controller design pattern.

## Technologies Used
- **Backend**: PHP, JavaScript
- **Frontend**: HTML, CSS, JavaScript, Bootstrap
- **Database**: MySQL
- **Other Tools**: XAMPP (for local server setup)

## Installation Instructions
1. Clone the repository:
   ```git clone https://github.com/your-repo/telecom-erp.git```
2. Set up a local server using XAMPP or similar.
3. Place the project folder in the `htdocs` directory of XAMPP.
4. Start Apache and MySQL from the XAMPP control
5. Import the database:
    - Open `phpMyAdmin` in your browser.
    - Create a new database named `telecom`.
    - Import the SQL file located at `assets/sql/crear_tablas.sql.`
6. Update the database connection settings in config/database.php:
```
<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "telecom";
```

## Usage
1. Open the application in your browser:
```http://localhost/telecom/index.php```
2. Register a new user or log in with an existing account.
3. Navigate through the menu to manage clients, providers, employees, products, and invoices.

## Contributing
Contributions are welcome! Please follow these steps:
1. Fork the repository.
2. Create a new branch for your feature or bug fix:
```git checkout -b feature-name```
3. Commit your changes and push to your fork.
4. Submit a pull request with a detailed description of your changes.

## Contact
For questions or support, please contact:
- [@zemanue](https://github.com/zemanue)
- [@ATreCro](https://github.com/ATreCro)
- [@nattfer](https://github.com/nattfer)
- [@Avalob](https://github.com/Avalob)


