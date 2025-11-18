<?php
header("Content-Type: application/json");

$respuesta = [
    "status" => "ok",
    "mensaje" => "Simulación exitosa",
    "tareas" => [
        ["id" => 1, "titulo" => "Comprar materiales", "categoria" => "Trabajo", "estado" => "Pendiente"],
        ["id" => 2, "titulo" => "Estudiar para el examen", "categoria" => "Importante", "estado" => "Pendiente"],
        ["id" => 3, "titulo" => "Lavar la ropa", "categoria" => "Personal", "estado" => "Completada"]
    ]
];

echo json_encode($respuesta);
?>

