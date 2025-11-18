<?php
class TareaControlador {
    public function listarTareas() {
        return [
            ["id" => 1, "titulo" => "Comprar materiales", "categoria" => "Trabajo", "estado" => "Pendiente"],
            ["id" => 2, "titulo" => "Avanzar proyecto", "categoria" => "Importante", "estado" => "Completada"],
            ["id" => 3, "titulo" => "Llamar al cliente", "categoria" => "Trabajo", "estado" => "Pendiente"],
        ];
    }
    public function agregarTarea($titulo, $categoria) {
        return true; // Simulación
    }
    public function completarTarea($id) {
        return true; // Simulación
    }
    public function eliminarTarea($id) {
        return true; // Simulación
    }
}
?>

