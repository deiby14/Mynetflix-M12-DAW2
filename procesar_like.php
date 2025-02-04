<?php
// Activar la visualización de errores para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();
require_once 'includes/peliculas_functions.php';

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

    $id_pelicula = $_POST['id_pelicula'];
    $id_usuario = $_SESSION['usuario']['id'];

    // Iniciar transacción
    $conn->beginTransaction();

    // Verificar si ya existe el like
    $stmt = $conn->prepare("SELECT id_like FROM Likes WHERE id_usuario = ? AND id_pelicula = ?");
    $stmt->execute([$id_usuario, $id_pelicula]);
    $like_exists = $stmt->fetch();

    // Obtener el número actual de likes antes de cualquier modificación
    $stmt = $conn->prepare("SELECT likes FROM Peliculas WHERE id_pelicula = ?");
    $stmt->execute([$id_pelicula]);
    $likes_actuales = $stmt->fetch(PDO::FETCH_ASSOC)['likes'];

    if ($like_exists) {
        // Eliminar like
        $stmt = $conn->prepare("DELETE FROM Likes WHERE id_usuario = ? AND id_pelicula = ?");
        $stmt->execute([$id_usuario, $id_pelicula]);
        $nuevo_total = max(0, $likes_actuales - 1); // Asegurar que no baje de 0
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

    // Debug
    error_log('Top 5 películas: ' . print_r($peliculas_top5, true));
    error_log('Todas las películas: ' . print_r($todas_peliculas, true));

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
    error_log("Error en procesar_like.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;
?> 