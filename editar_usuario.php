<?php
include 'conexion.php';

// Verificar si se recibieron los datos
if (isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['estado']) && isset($_POST['es_admin'])&& isset($_POST['es_admin'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $estado = $_POST['estado'];
    $es_admin = $_POST['es_admin'];

    try {
        // Actualizar el usuario existente sin cambiar la contrasena
        $stmt = $pdo->prepare("UPDATE Usuarios SET nombre = :nombre, email = :email, estado = :estado, es_admin = :es_admin WHERE id_usuario = :id");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':es_admin', $es_admin);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Usuario actualizado con éxito']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error: Datos incompletos']);
}
?>
