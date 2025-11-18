<?php
require_once "../Modelo/conexion.php";
require_once "../Modelo/tareaModelo.php";

$id = $_POST['id'];
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$categoria = $_POST['categoria'];

$t = new TareaModelo();
$t->editarTarea($id, $titulo, $descripcion, $categoria);

echo "ok";
