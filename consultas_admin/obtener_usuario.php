<?php
include '../conexion.php';

$id_usuario = htmlspecialchars($_POST['id_usuario']);

// Preparamos la consulta para obtener los datos del usuario
$stmt = $conn->prepare("SELECT id_usuario, nombre, email, es_admin, estado FROM usuarios WHERE id_usuario = :id_usuario");
$stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    echo json_encode($usuario);
} else {
    echo "Usuario no encontrado";
}
?>
