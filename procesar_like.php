<?php
header('Content-Type: application/json');
session_start();
require_once 'includes/peliculas_functions.php';

try {
    require_once 'conexion.php';
    
    if (!isset($_SESSION['usuario'])) {
        throw new Exception('Usuario no autenticado');
    }

    $userId = $_SESSION['usuario']['id'];
    $peliculaId = $_POST['id_pelicula'];
    $likes_order = $_POST['likes_order'] ?? '';

    $conn->beginTransaction();

    // Verificar si ya existe el like
    $stmt = $conn->prepare("SELECT id_like FROM Likes WHERE id_usuario = ? AND id_pelicula = ?");
    $stmt->execute([$userId, $peliculaId]);
    $like_exists = $stmt->fetch();

    if ($like_exists) {
        $stmt = $conn->prepare("DELETE FROM Likes WHERE id_usuario = ? AND id_pelicula = ?");
        $stmt->execute([$userId, $peliculaId]);
        $stmt = $conn->prepare("UPDATE Peliculas SET likes = likes - 1 WHERE id_pelicula = ?");
        $stmt->execute([$peliculaId]);
        $accion = 'unlike';
    } else {
        $stmt = $conn->prepare("INSERT INTO Likes (id_usuario, id_pelicula) VALUES (?, ?)");
        $stmt->execute([$userId, $peliculaId]);
        $stmt = $conn->prepare("UPDATE Peliculas SET likes = likes + 1 WHERE id_pelicula = ?");
        $stmt->execute([$peliculaId]);
        $accion = 'like';
    }

    // Obtener nuevo número de likes
    $stmt = $conn->prepare("SELECT likes FROM Peliculas WHERE id_pelicula = ?");
    $stmt->execute([$peliculaId]);
    $nuevo_total = $stmt->fetch(PDO::FETCH_ASSOC)['likes'];

    $conn->commit();

    // Obtener Top 5 actualizado (siempre)
    $top5 = getTop5Peliculas($userId);

    // Obtener todas las películas solo si hay orden por likes
    $todas = null;
    if (!empty($likes_order)) {
        $todas = getPeliculasOrdenadas($userId, ['likes_order' => $likes_order]);
    }

    // Enviar respuesta JSON
    echo json_encode([
        'success' => true,
        'likes' => $nuevo_total,
        'accion' => $accion,
        'top5' => $top5,
        'todas' => $todas
    ]);

} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 