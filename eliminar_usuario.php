<?php
include 'conexion.php';

// Verificar si se recibió el ID
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        // Preparar la consulta para eliminar el usuario
        $stmt = $conn->prepare("DELETE FROM Usuarios WHERE id_usuario = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Verificar si se eliminó alguna fila
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado con éxito']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el usuario para eliminar']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ID no proporcionado']);
}
?>
