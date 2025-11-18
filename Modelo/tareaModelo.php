<?php
require_once "conexion.php";

class TareaModelo {

    private $db;

    public function __construct() {
        $this->db = (new Conexion())->conectar();
    }

    // ========== LISTAR TAREAS ==========
    public function listarTareas() {
        $sql = "SELECT * FROM tareas ORDER BY id DESC";
        $result = $this->db->query($sql);

        $tareas = [];

        while ($row = $result->fetch_assoc()) {
            $tareas[] = $row;
        }

        return $tareas;
    }

    // ========== AGREGAR TAREA ==========
    public function agregarTarea($titulo, $descripcion, $categoria) {
        $sql = "INSERT INTO tareas (titulo, descripcion, categoria, completado)
                VALUES (?, ?, ?, 0)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $titulo, $descripcion, $categoria);

        return $stmt->execute();
    }

    // ========== EDITAR TAREA ==========
    public function editarTarea($id, $titulo, $descripcion, $categoria) {
        $sql = "UPDATE tareas 
                SET titulo = ?, descripcion = ?, categoria = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssi", $titulo, $descripcion, $categoria, $id);

        return $stmt->execute();
    }

    // ========== CAMBIAR ESTADO ==========
    public function cambiarEstado($id, $estado) {
        $sql = "UPDATE tareas SET completado = ? WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $estado, $id);

        return $stmt->execute();
    }

    // ========== ELIMINAR TAREA ==========
    public function eliminarTarea($id) {
        $sql = "DELETE FROM tareas WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
