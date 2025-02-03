<?php
require_once 'conexion.php';
header('Content-Type: application/json');

try {
    if (empty($_POST['id_usuario']) || empty($_POST['accion'])) {
        throw new Exception('Datos insuficientes');
    }

    $id_usuario = $_POST['id_usuario'];
    $accion = $_POST['accion']; // "aprobar" o "rechazar"

    // Solo permitir valores válidos
    if (!in_array($accion, ['aprobar', 'rechazar'])) {
        throw new Exception('Acción no válida');
    }

    // Determinar el nuevo estado
    $nuevo_estado = ($accion === 'aprobar') ? 'aprobado' : 'rechazado';

    // Actualizar estado del usuario
    $stmt = $conn->prepare("UPDATE Usuarios SET estado = ? WHERE id_usuario = ?");
    $stmt->execute([$nuevo_estado, $id_usuario]);

    echo json_encode(['success' => true, 'message' => "Usuario $nuevo_estado correctamente."]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
