<?php
require_once "../Modelo/conexion.php";
require_once "../Modelo/tareaModelo.php";

header('Content-Type: application/json');

$t = new TareaModelo();
echo json_encode($t->listarTareas());
