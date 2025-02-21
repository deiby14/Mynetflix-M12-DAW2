<?php
require_once 'includes/session_check.php';
require_once 'includes/peliculas_functions.php';

function getDirectores() {
    global $conn;
    $stmt = $conn->prepare("SELECT DISTINCT director FROM Peliculas ORDER BY director");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

try {
    $userId = isset($_SESSION['usuario']) ? $_SESSION['usuario']['id'] : null;
    
    // Obtener películas top 5
    $peliculas_top5 = getTop5Peliculas($userId);
    error_log("Top 5 películas obtenidas: " . count($peliculas_top5));

    // Obtener todas las películas
    $todas_peliculas = getPeliculasOrdenadas($userId);
    error_log("Todas las películas obtenidas: " . count($todas_peliculas));

} catch (PDOException $e) {
    error_log("Error al obtener las películas: " . $e->getMessage());
    $peliculas_top5 = [];
    $todas_peliculas = [];
}
?>
