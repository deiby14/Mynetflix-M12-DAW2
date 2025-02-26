<?php
include '../conexion.php';

$id_usuario = htmlspecialchars($_POST['id_usuario']);
$nombre     = htmlspecialchars($_POST['nombre']);
$email      = htmlspecialchars($_POST['email']);
$contrasena = htmlspecialchars($_POST['contrasena']);
$es_admin   = htmlspecialchars($_POST['es_admin']);
$estado     = htmlspecialchars($_POST['estado']);

try {
    // Si se envía una contraseña nueva, la actualizamos; de lo contrario, no se modifica
    if (!empty($contrasena)) {
        $sql = $conn->prepare("UPDATE usuarios 
                                SET nombre = :nombre, email = :email, contrasena = :contrasena, 
                                    es_admin = :es_admin, estado = :estado 
                                WHERE id_usuario = :id_usuario");
        $sql->bindParam(":contrasena", $contrasena, PDO::PARAM_STR);
    } else {
        $sql = $conn->prepare("UPDATE usuarios 
                                SET nombre = :nombre, email = :email, 
                                    es_admin = :es_admin, estado = :estado 
                                WHERE id_usuario = :id_usuario");
    }
    $sql->bindParam(":nombre", $nombre, PDO::PARAM_STR);
    $sql->bindParam(":email", $email, PDO::PARAM_STR);
    $sql->bindParam(":es_admin", $es_admin, PDO::PARAM_STR);
    $sql->bindParam(":estado", $estado, PDO::PARAM_STR);
    $sql->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
    $sql->execute();

    echo json_encode(["status" => "success"]);
} catch (PDOException $e) {
    echo "conexion fallida" . $e->getMessage();
}
?>
