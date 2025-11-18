<?php
session_start();
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$nombre   = trim($_POST["nombre"] ?? "");
$email    = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");
$rol      = trim($_POST["rol"] ?? "usuario"); 

if ($nombre === "" || $email === "" || $password === "") {
    echo json_encode(["error" => "Todos los campos son obligatorios"]);
    exit;
}

if ($rol !== "admin" && $rol !== "usuario") {
    $rol = "usuario"; 
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["error" => "Correo inválido"]);
    exit;
}

$sql = "SELECT id FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["error" => "El correo ya está registrado"]);
    exit;
}

$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $email, $hash, $rol);

if ($stmt->execute()) {
    $_SESSION["usuario_id"] = $stmt->insert_id;
    $_SESSION["nombre"] = $nombre;
    $_SESSION["rol"] = $rol;

    echo json_encode(["success" => true, "rol" => $rol]);
} else {
    echo json_encode(["error" => "Error al registrar usuario"]);
}

$stmt->close();
$conn->close();
?>
