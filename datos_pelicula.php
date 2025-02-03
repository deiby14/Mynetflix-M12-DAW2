<?php
require_once 'includes/session_check.php';
require_once 'includes/peliculas_functions.php';

try {
    $userId = isset($_SESSION['usuario']) ? $_SESSION['usuario']['id'] : null;
    $peliculas_top5 = getTop5Peliculas($userId);
    $todas_peliculas = getPeliculasOrdenadas($userId);
} catch (PDOException $e) {
    echo "Error al obtener las películas: " . $e->getMessage();
}
?>
