<?php
session_start();
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$email    = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");

if ($email === "" || $password === "") {
    echo json_encode(["error" => "Por favor complete todos los campos"]);
    exit;
}

$sql = "SELECT id, nombre, password, rol FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["error" => "Correo o contraseña incorrectos"]);
    exit;
}

$stmt->bind_result($id, $nombre, $hash, $rol);
$stmt->fetch();

if (!password_verify($password, $hash)) {
    echo json_encode(["error" => "Correo o contraseña incorrectos"]);
    exit;
}

$_SESSION["usuario_id"] = $id;
$_SESSION["nombre"]     = $nombre;
$_SESSION["rol"]        = $rol;

echo json_encode([
    "success" => true,
    "rol"     => $rol,
    "nombre"  => $nombre
]);

$stmt->close();
$conn->close();
?>
