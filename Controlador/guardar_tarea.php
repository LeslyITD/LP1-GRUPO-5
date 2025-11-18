<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../Modelo/conexion.php";
require_once "../Modelo/tareaModelo.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoria = $_POST['categoria'] ?? '';

    $tarea = new TareaModelo();
    $resultado = $tarea->agregarTarea($titulo, $descripcion, $categoria);

    echo $resultado ? "ok" : "error";

} else {
    echo "Método incorrecto (usa POST)";
}
