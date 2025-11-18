<?php
require_once "../Modelo/usuarioModelo.php";

class UsuarioControlador {

    public function listar() {
        $usuario = new UsuarioModelo();
        $datos = $usuario->obtenerUsuarios();
        include "../Vista/usuarioLista.php";
    }

    public function guardar() {
        $usuario = new UsuarioModelo();
        $usuario->guardarUsuario($_POST['nombre'], $_POST['correo']);
        header("Location: ../Vista/usuarioLista.php");
    }

    public function eliminar() {
        $usuario = new UsuarioModelo();
        $usuario->eliminarUsuario($_GET['id']);
        header("Location: ../Vista/usuarioLista.php");
    }
}
?>
