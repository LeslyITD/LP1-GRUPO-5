<?php
class Conexion {
    public function conectar(){
        $conn = new mysqli("localhost", "root", "", "todolist_grupo5");

        if($conn->connect_error){
            die("Error de conexión: " . $conn->connect_error);
        }

        return $conn;
    }
}
