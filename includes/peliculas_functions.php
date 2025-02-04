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
    
    $query = "SELECT p.*, 0 as user_liked 
              FROM Peliculas p 
              ORDER BY p.titulo ASC";
    
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

function getPeliculasFiltradas($filtros, $userId) {
    global $conn;
    
    // Query base simplificada para pruebas
    $query = "SELECT p.*, 
              CASE WHEN l.id_like IS NOT NULL THEN 1 ELSE 0 END as user_liked
              FROM Peliculas p
              LEFT JOIN Likes l ON p.id_pelicula = l.id_pelicula AND l.id_usuario = ?";
    
    $params = [$userId];
    
    // Añadir condiciones de filtro solo si hay valores
    $where = [];
    
    if (!empty($filtros['titulo'])) {
        $where[] = "p.titulo LIKE ?";
        $params[] = "%" . $filtros['titulo'] . "%";
    }
    
    if (!empty($filtros['categoria'])) {
        $where[] = "p.categoria = ?";
        $params[] = $filtros['categoria'];
    }
    
    if (!empty($filtros['director'])) {
        $where[] = "p.director = ?";
        $params[] = $filtros['director'];
    }
    
    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }
    
    // Ordenar
    if (!empty($filtros['likes_order'])) {
        $query .= " ORDER BY p.likes " . ($filtros['likes_order'] === 'desc' ? 'DESC' : 'ASC');
    } else {
        $query .= " ORDER BY p.titulo ASC";
    }
    
    error_log("Query: " . $query); // Debug
    error_log("Params: " . print_r($params, true)); // Debug
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("Resultados encontrados: " . count($result)); // Debug
    return $result;
}

function getDirectores() {
    global $conn;
    $stmt = $conn->query("SELECT DISTINCT director FROM Peliculas ORDER BY director");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?> 