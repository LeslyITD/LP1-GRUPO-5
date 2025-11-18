<?php
require_once("conexion.php");

$conexion = new Conexion();
$db = $conexion->conectar();

if($db){
    echo "Conexión exitosa!";
}
?>
