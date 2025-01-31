<?php
require_once 'conexion.php';

function getPeliculasWithUserLikes($userId) {
    global $conn;
    
    $query = "SELECT p.*, 
              CASE WHEN l.id_like IS NOT NULL THEN 1 ELSE 0 END as user_liked
              FROM Peliculas p
              LEFT JOIN Likes l ON p.id_pelicula = l.id_pelicula AND l.id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$userId ?? 0]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTop5Peliculas($userId = null) {
    global $conn;
    
    $query = "SELECT p.*, 
              CASE WHEN l.id_like IS NOT NULL THEN 1 ELSE 0 END as user_liked
              FROM Peliculas p
              LEFT JOIN Likes l ON p.id_pelicula = l.id_pelicula AND l.id_usuario = ?
              ORDER BY p.likes DESC 
              LIMIT 5";
    $stmt = $conn->prepare($query);
    $stmt->execute([$userId ?? 0]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllPeliculas() {
    global $conn;
    
    $query = "SELECT * FROM Peliculas ORDER BY likes DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPeliculaById($id) {
    global $conn;
    
    $query = "SELECT * FROM Peliculas WHERE id_pelicula = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPeliculasOrdenadas($userId = null) {
    global $conn;
    
    $query = "SELECT p.*, 
              CASE WHEN l.id_like IS NOT NULL THEN 1 ELSE 0 END as user_liked
              FROM Peliculas p
              LEFT JOIN Likes l ON p.id_pelicula = l.id_pelicula AND l.id_usuario = ?
              ORDER BY p.titulo ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute([$userId ?? 0]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?> 