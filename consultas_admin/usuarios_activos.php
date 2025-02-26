<?php
require_once "../conexion.php";

$stmt = $conn->query("SELECT * FROM Usuarios WHERE estado = 'activo'");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($usuarios);


