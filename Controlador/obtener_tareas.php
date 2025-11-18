<?php
require_once "../Modelo/conexion.php";
require_once "../Modelo/tareaModelo.php";

header('Content-Type: application/json');

$tarea = new TareaModelo();
$lista = $tarea->listarTareas();

echo json_encode($lista, JSON_UNESCAPED_UNICODE);
