<?php
require 'conexion.php';

try {
    $top_limit = 5;
    $query_top5 = "SELECT id_pelicula, titulo, poster_url, likes FROM Peliculas ORDER BY likes DESC LIMIT :limit";
    $stmt_top5 = $conn->prepare($query_top5);
    $stmt_top5->bindParam(':limit', $top_limit, PDO::PARAM_INT);
    $stmt_top5->execute();
    $peliculas_top5 = $stmt_top5->fetchAll(PDO::FETCH_ASSOC);

    $query_peliculas = "SELECT id_pelicula, titulo, poster_url, likes FROM Peliculas ORDER BY titulo ASC";
    $stmt_peliculas = $conn->prepare($query_peliculas);
    $stmt_peliculas->execute();
    $todas_peliculas = $stmt_peliculas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener las películas: " . $e->getMessage();
}
?>
