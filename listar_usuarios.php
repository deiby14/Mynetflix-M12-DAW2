<?php
include 'conexion.php';

try {
    $stmt = $conn->query("SELECT id_usuario, nombre, email, estado, es_admin FROM Usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($usuarios);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?> 