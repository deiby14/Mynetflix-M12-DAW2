<?php
// Activar la visualización de errores para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

try {
    // Corregir la ruta del archivo de conexión
    require_once 'conexion.php';  // Ya no buscará en includes/

    // Debug: Verificar conexión
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos');
    }

    // Debug: Verificar sesión
    if (!isset($_SESSION['usuario'])) {
        throw new Exception('Usuario no autenticado');
    }

    // Debug: Verificar datos POST
    if (!isset($_POST['id_pelicula'])) {
        throw new Exception('ID de película no proporcionado');
    }

    // Debug: Imprimir valores
    error_log("ID Usuario: " . $_SESSION['usuario']['id']);
    error_log("ID Película: " . $_POST['id_pelicula']);

    $id_usuario = $_SESSION['usuario']['id'];
    $id_pelicula = $_POST['id_pelicula'];

    // Verificar si ya existe el like
    $stmt = $conn->prepare("SELECT id_like FROM Likes WHERE id_usuario = ? AND id_pelicula = ?");
    if (!$stmt) {
        throw new Exception('Error preparando la consulta: ' . implode(' ', $conn->errorInfo()));
    }
    
    $stmt->execute([$id_usuario, $id_pelicula]);
    $like_existente = $stmt->fetch();

    $conn->beginTransaction();

    if ($like_existente) {
        // Eliminar like
        $stmt = $conn->prepare("DELETE FROM Likes WHERE id_usuario = ? AND id_pelicula = ?");
        $stmt->execute([$id_usuario, $id_pelicula]);
        
        // Actualizar contador
        $stmt = $conn->prepare("UPDATE Peliculas SET likes = likes - 1 WHERE id_pelicula = ?");
        $stmt->execute([$id_pelicula]);
        
        $accion = 'unlike';
    } else {
        // Añadir like
        $stmt = $conn->prepare("INSERT INTO Likes (id_usuario, id_pelicula) VALUES (?, ?)");
        $stmt->execute([$id_usuario, $id_pelicula]);
        
        // Actualizar contador
        $stmt = $conn->prepare("UPDATE Peliculas SET likes = likes + 1 WHERE id_pelicula = ?");
        $stmt->execute([$id_pelicula]);
        
        $accion = 'like';
    }

    // Obtener el nuevo número de likes
    $stmt = $conn->prepare("SELECT likes FROM Peliculas WHERE id_pelicula = ?");
    $stmt->execute([$id_pelicula]);
    $nuevo_contador = $stmt->fetchColumn();

    $conn->commit();

    echo json_encode([
        'success' => true,
        'accion' => $accion,
        'likes' => $nuevo_contador
    ]);

} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // Debug: Registrar el error
    error_log("Error en procesar_like.php: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}

exit;
?> 