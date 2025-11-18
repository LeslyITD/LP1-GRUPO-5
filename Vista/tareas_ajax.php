<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../Modelo/conexion.php";
require_once "../Modelo/tareaModelo.php";

$tarea = new TareaModelo();

$accion = $_POST['accion'] ?? '';

switch ($accion) {

    case "listar":
        $datos = $tarea->listarTareas();
        echo json_encode($datos);
        break;

    case "agregar":
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $categoria = $_POST['categoria'] ?? '';

        $resultado = $tarea->agregarTarea($titulo, $descripcion, $categoria);
        echo $resultado ? "1" : "0";
        break;

    case "estado":
        $id = $_POST['id'] ?? 0;
        $estado = $_POST['estado'] ?? 0;

        $resultado = $tarea->cambiarEstado($id, $estado);
        echo $resultado ? "1" : "0";
        break;

    case "eliminar":
        $id = $_POST['id'] ?? 0;

        $resultado = $tarea->eliminarTarea($id);
        echo $resultado ? "1" : "0";
        break;

    default:
        echo "Acción no válida";
}
