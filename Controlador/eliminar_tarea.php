<?php
require_once "../Modelo/conexion.php";
require_once "../Modelo/tareaModelo.php";

$id = $_POST['id'];

$t = new TareaModelo();
$t->eliminarTarea($id);

echo "ok";
