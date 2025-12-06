<?php
require_once 'conexion.php';

$conn = new Conn();
$conexion = $conn->conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba de Conexión</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #4CAF50; color: white; }
    </style>
</head>
<body>
    <h1>Prueba de Conexión</h1>
    
    <?php if ($conexion): ?>
        <p style="color: green;">Conexión exitosa a la base de datos</p>
    <?php endif; ?>

    <h2>Categorías</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Color</th>
        </tr>
        <?php
        $resultado = $conexion->query("SELECT * FROM categorias");
        $categorias = $resultado->fetchAll();
        foreach ($categorias as $fila) {
            echo "<tr>";
            echo "<td>" . $fila['id'] . "</td>";
            echo "<td>" . $fila['nombre'] . "</td>";
            echo "<td>" . $fila['color'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2>Tareas</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Categoría</th>
            <th>Completada</th>
        </tr>
        <?php
        $resultado = $conexion->query("SELECT t.*, c.nombre as categoria_nombre FROM tareas t LEFT JOIN categorias c ON t.categoria_id = c.id");
        $tareas = $resultado->fetchAll();
        foreach ($tareas as $fila) {
            echo "<tr>";
            echo "<td>" . $fila['id'] . "</td>";
            echo "<td>" . $fila['titulo'] . "</td>";
            echo "<td>" . ($fila['categoria_nombre'] ?? 'Sin categoría') . "</td>";
            echo "<td>" . ($fila['completada'] ? 'Sí' : 'No') . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <?php $conn->cerrar(); ?>
</body>
</html>
