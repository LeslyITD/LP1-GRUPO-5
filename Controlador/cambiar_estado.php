<?php
require_once "../Modelo/conexion.php";
require_once "../Modelo/tareaModelo.php";

$id = $_POST['id'];
$estado = $_POST['estado'];

$t = new TareaModelo();
$t->cambiarEstado($id, $estado);

echo "ok";
