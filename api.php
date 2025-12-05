<?php
require_once 'conexion.php';

$conn = new Conn();
$conexion = $conn->conectar();

$accion = $_REQUEST['accion'] ?? '';

// Obtener categorías
if ($accion == 'obtener_categorias') {
    $sql = "SELECT * FROM categorias ORDER BY nombre";
    $resultado = $conexion->query($sql);
    $categorias = $resultado->fetchAll();
    echo json_encode(['exito' => true, 'datos' => $categorias]);
}

// Obtener tareas
if ($accion == 'obtener_tareas') {
    $filtro = $_GET['filtro'] ?? 'todas';
    $categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
    
    $sql = "SELECT t.*, c.nombre as categoria_nombre, c.color as categoria_color 
            FROM tareas t 
            LEFT JOIN categorias c ON t.categoria_id = c.id";
    
    $where = [];
    if ($filtro == 'completadas') $where[] = "t.completada = 1";
    if ($filtro == 'pendientes') $where[] = "t.completada = 0";
    if ($categoria > 0) $where[] = "t.categoria_id = $categoria";
    
    if (count($where) > 0) $sql .= ' WHERE ' . implode(' AND ', $where);
    $sql .= ' ORDER BY t.id DESC';
    
    $resultado = $conexion->query($sql);
    $tareas = $resultado->fetchAll();
    echo json_encode(['exito' => true, 'datos' => $tareas]);
}

// Agregar tarea
if ($accion == 'agregar_tarea') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoria_id = isset($_POST['categoria_id']) && $_POST['categoria_id'] != '' ? intval($_POST['categoria_id']) : null;
    
    if ($categoria_id) {
        $sql = "INSERT INTO tareas (titulo, descripcion, categoria_id) VALUES ('$titulo', '$descripcion', $categoria_id)";
    } else {
        $sql = "INSERT INTO tareas (titulo, descripcion) VALUES ('$titulo', '$descripcion')";
    }
    $conexion->query($sql);
    echo json_encode(['exito' => true]);
}

// Toggle completada
if ($accion == 'toggle_completada') {
    $id = intval($_POST['id']);
    $sql = "UPDATE tareas SET completada = NOT completada WHERE id = $id";
    $conexion->query($sql);
    echo json_encode(['exito' => true]);
}

// Eliminar tarea
if ($accion == 'eliminar_tarea') {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM tareas WHERE id = $id";
    $conexion->query($sql);
    echo json_encode(['exito' => true]);
}

// Editar tarea
if ($accion == 'editar_tarea') {
    $id = intval($_POST['id']);
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $categoria_id = isset($_POST['categoria_id']) && $_POST['categoria_id'] != '' ? intval($_POST['categoria_id']) : null;
    
    if ($categoria_id) {
        $sql = "UPDATE tareas SET titulo = '$titulo', descripcion = '$descripcion', categoria_id = $categoria_id WHERE id = $id";
    } else {
        $sql = "UPDATE tareas SET titulo = '$titulo', descripcion = '$descripcion', categoria_id = NULL WHERE id = $id";
    }
    $conexion->query($sql);
    echo json_encode(['exito' => true]);
}

$conn->cerrar();
?>
