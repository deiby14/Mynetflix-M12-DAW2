<?php
header('Content-Type: application/json');
session_start();
require_once 'includes/peliculas_functions.php';

try {
    require_once 'conexion.php';
    
    if (!isset($_SESSION['usuario'])) {
        throw new Exception('Usuario no autenticado');
    }

    if (!isset($_POST['id_pelicula'])) {
        throw new Exception('ID de película no proporcionado');
    }

    $id_pelicula = $_POST['id_pelicula'];
    $id_usuario = $_SESSION['usuario']['id'];

    // Iniciar transacción
    $conn->beginTransaction();

    // Verificar si ya existe el like
    $stmt = $conn->prepare("SELECT id_like FROM Likes WHERE id_usuario = ? AND id_pelicula = ?");
    $stmt->execute([$id_usuario, $id_pelicula]);
    $like_exists = $stmt->fetch();

    // Obtener el número actual de likes
    $stmt = $conn->prepare("SELECT likes FROM Peliculas WHERE id_pelicula = ?");
    $stmt->execute([$id_pelicula]);
    $likes_actuales = $stmt->fetch(PDO::FETCH_ASSOC)['likes'];

    if ($like_exists) {
        // Eliminar like
        $stmt = $conn->prepare("DELETE FROM Likes WHERE id_usuario = ? AND id_pelicula = ?");
        $stmt->execute([$id_usuario, $id_pelicula]);
        $nuevo_total = max(0, $likes_actuales - 1);
        $accion = 'unlike';
    } else {
        // Añadir like
        $stmt = $conn->prepare("INSERT INTO Likes (id_usuario, id_pelicula) VALUES (?, ?)");
        $stmt->execute([$id_usuario, $id_pelicula]);
        $nuevo_total = $likes_actuales + 1;
        $accion = 'like';
    }

    // Actualizar el contador en la tabla Peliculas
    $stmt = $conn->prepare("UPDATE Peliculas SET likes = ? WHERE id_pelicula = ?");
    $stmt->execute([$nuevo_total, $id_pelicula]);

    $conn->commit();

    // Obtener las listas actualizadas
    $peliculas_top5 = getTop5Peliculas($id_usuario);
    $todas_peliculas = getPeliculasOrdenadas($id_usuario);

    echo json_encode([
        'success' => true,
        'accion' => $accion,
        'likes' => $nuevo_total,
        'actualizacion' => [
            'top5' => $peliculas_top5,
            'todas' => $todas_peliculas
        ]
    ]);

} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 