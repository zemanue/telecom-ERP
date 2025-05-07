<?php

$conexion = mysqli_connect("localhost", "root", "", "telecom");
mysqli_set_charset($conexion, "utf8");

/*Para comprobar conexion
if($conexion){
    echo 'conectado exitosamente';
}else{
    echo 'no se puede conectar';
}*/
