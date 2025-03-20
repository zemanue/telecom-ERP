function verificarTabla(tabla, mensaje, redireccion) {
    fetch(`../controllers/verificar_registros.php?tabla=${tabla}`)
        .then(response => response.json())
        .then(data => {
            if (data.total === 0) {
                if (confirm(mensaje)) {
                    window.location.href = redireccion;
                }
            } else {
                console.log(`Se ha verificado que hay registros en ${tabla}`);
                if (tabla === 'proveedor') {
                    window.location.href = 'productos.php';
                } else if (tabla === 'almacen') {
                    window.location.href = 'almacenes.php';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al verificar');
        });
}

function verificarProveedores() {
    verificarTabla('proveedor', "No hay proveedores registrados. ¿Desea ir a proveedores?", 'proveedores.php?accion=crear');
    return false;
}

function verificarAlmacenes() {
    verificarTabla('almacen', "No hay almacenes registrados. ¿Desea ir a almacenes?", 'almacenes.php?accion=crear');
    return false;
}
