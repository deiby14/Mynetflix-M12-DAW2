<?php

include '../conexion.php';

$nombre = htmlspecialchars($_POST['nombre']); 
$email = htmlspecialchars($_POST['email']); 
$contrasena = htmlspecialchars($_POST['contrasena']); 
$es_admin = htmlspecialchars($_POST['es_admin']); 
$estado = htmlspecialchars($_POST['estado']); 

try {
    $sql = $conn->prepare("INSERT INTO usuarios (nombre, email, contrasena,es_admin,estado) VALUES (:nombre, :email, :contrasena,:es_admin,:estado)");
    $sql->bindParam(":nombre", $nombre, PDO::PARAM_STR_CHAR);
    $sql->bindParam(":email", $email, PDO::PARAM_STR_CHAR);
    $sql->bindParam(":contrasena", $contrasena, PDO::PARAM_STR_CHAR);
    $sql->bindParam(":es_admin", $es_admin, PDO::PARAM_STR_CHAR);
    $sql->bindParam(":estado", $estado, PDO::PARAM_STR_CHAR);
    $sql->execute();
    
    echo json_encode('craeado');

} catch (PDOException $e) {
    echo "conexion fallida" . $e->getMessage();
}