<?php

include '../conexion.php'; // Incluye la conexión a la base de datos

$id_usuario = $_POST['id_usuario'];

try {
    $sqlEliminar = "UPDATE usuarios SET estado = 'activo' WHERE id_usuario = :id_usuario";
    $stmtEliminar = $conn->prepare($sqlEliminar);
    $stmtEliminar->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmtEliminar->execute();

    echo json_encode('success');
}

 catch (PDOException $e) {
    echo "conexion fallida" . $e->getMessage();
}