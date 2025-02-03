<?php
require_once 'conexion.php';
header('Content-Type: application/json');

$stmt = $conn->query("SELECT id_usuario, nombre, email, estado FROM Usuarios WHERE estado = 'pendiente'");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($usuarios);
?>
