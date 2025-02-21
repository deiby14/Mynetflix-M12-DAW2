<?php
session_start();
require_once 'includes/peliculas_functions.php';
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 0);

// Log para debug
error_log("procesar_filtros.php fue llamado");

try {
    // Obtener el ID de usuario si está autenticado
    $userId = isset($_SESSION['usuario']) ? $_SESSION['usuario']['id'] : null;

    // Log de los parámetros recibidos
    error_log("Parámetros GET recibidos: " . print_r($_GET, true));

    // Recoger los parámetros de filtrado
    $filtros = [
        'titulo' => $_GET['titulo'] ?? '',
        'director' => $_GET['director'] ?? '',
        'likes_order' => $_GET['likes_order'] ?? '',
        'generos' => $_GET['generos'] ?? '',
        'user_likes' => $_GET['user_likes'] ?? ''
    ];

    error_log("Procesando filtros: " . print_r($filtros, true));
    
    // Obtener películas filtradas
    $peliculas = getPeliculasOrdenadas($userId, $filtros);
    $peliculas_top5 = getTop5Peliculas($userId);
    
    // Generar HTML para cada película
    $html = '';
    foreach ($peliculas as $pelicula) {
        $html .= generatePeliculaHTML($pelicula);
    }
    
    error_log("Películas filtradas: " . print_r($peliculas, true));
    
    echo json_encode([
        'success' => true,
        'html' => $html,
        'actualizacion' => [
            'top5' => $peliculas_top5,
            'todas' => $peliculas
        ],
        'debug' => [
            'filtros' => $filtros,
            'total_peliculas' => count($peliculas)
        ]
    ]);

} catch (Exception $e) {
    error_log("Error en procesar_filtros.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
}


function generatePeliculaHTML($pelicula) {
    return '
    <div class="col-6 col-md-4 col-lg-2 mb-4">
        <div class="movie-card text-center">
            <a href="detalle_pelicula.php?id=' . htmlspecialchars($pelicula['id_pelicula']) . '">
                <img src="./img/' . htmlspecialchars($pelicula['poster_url']) . '" 
                     alt="' . htmlspecialchars($pelicula['titulo']) . '"
                     class="img-fluid">
            </a>
            <h5 class="movie-title">' . htmlspecialchars($pelicula['titulo']) . '</h5>
            <p class="movie-genres">' . htmlspecialchars(str_replace(',', ', ', $pelicula['generos'])) . '</p>
            <button class="like-btn ' . ($pelicula['user_liked'] ? 'liked' : '') . '"
                    data-pelicula-id="' . htmlspecialchars($pelicula['id_pelicula']) . '">
                <i class="fas fa-thumbs-up"></i> Like
            </button>
            <div class="like-count" id="likes-' . htmlspecialchars($pelicula['id_pelicula']) . '">
                ' . htmlspecialchars($pelicula['likes']) . ' Likes
            </div>
        </div>
    </div>';
} 