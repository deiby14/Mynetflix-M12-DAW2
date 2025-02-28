<?php
require_once "../conexion.php";

$stmt = $conn->query("SELECT * FROM Usuarios WHERE estado = 'pendiente'");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($usuarios);


