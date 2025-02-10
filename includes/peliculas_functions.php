<?php
require_once 'conexion.php';

function getPeliculasWithUserLikes($userId) {
    global $conn;
    
    $query = "SELECT p.*, 
              GROUP_CONCAT(g.nombre) as generos,
              CASE WHEN l.id_like IS NOT NULL THEN 1 ELSE 0 END as user_liked
              FROM Peliculas p
              LEFT JOIN Likes l ON p.id_pelicula = l.id_pelicula AND l.id_usuario = ?
              LEFT JOIN Peliculas_Generos pg ON p.id_pelicula = pg.id_pelicula
              LEFT JOIN Generos g ON pg.id_genero = g.id_genero
              GROUP BY p.id_pelicula";
    $stmt = $conn->prepare($query);
    $stmt->execute([$userId ?? 0]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTop5Peliculas($userId) {
    global $conn;
    
    $query = "SELECT p.*, 
              GROUP_CONCAT(g.nombre) as generos,
              CASE WHEN l_user.id_like IS NOT NULL THEN 1 ELSE 0 END as user_liked,
              p.likes as likes
              FROM Peliculas p
              LEFT JOIN Likes l_user ON p.id_pelicula = l_user.id_pelicula AND l_user.id_usuario = ?
              LEFT JOIN Peliculas_Generos pg ON p.id_pelicula = pg.id_pelicula
              LEFT JOIN Generos g ON pg.id_genero = g.id_genero
              GROUP BY p.id_pelicula
              ORDER BY p.likes DESC, p.titulo ASC
              LIMIT 5";
    
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en getTop5Peliculas: " . $e->getMessage());
        return [];
    }
}

function getAllPeliculas() {
    global $conn;
    
    $query = "SELECT p.*, 
              GROUP_CONCAT(g.nombre) as generos,
              0 as user_liked 
              FROM Peliculas p 
              LEFT JOIN Peliculas_Generos pg ON p.id_pelicula = pg.id_pelicula
              LEFT JOIN Generos g ON pg.id_genero = g.id_genero
              GROUP BY p.id_pelicula
              ORDER BY p.titulo ASC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPeliculaById($id) {
    global $conn;
    
    $query = "SELECT p.*, GROUP_CONCAT(g.nombre) as generos 
              FROM Peliculas p
              LEFT JOIN Peliculas_Generos pg ON p.id_pelicula = pg.id_pelicula
              LEFT JOIN Generos g ON pg.id_genero = g.id_genero
              WHERE p.id_pelicula = :id
              GROUP BY p.id_pelicula";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPeliculasOrdenadas($userId, $filtros = []) {
    global $conn;
    
    // Query base para seleccionar películas con sus géneros y likes
    $query = "SELECT DISTINCT p.*, 
              GROUP_CONCAT(DISTINCT g.nombre) as generos,
              CASE WHEN l_user.id_like IS NOT NULL THEN 1 ELSE 0 END as user_liked
              FROM Peliculas p
              LEFT JOIN Peliculas_Generos pg ON p.id_pelicula = pg.id_pelicula
              LEFT JOIN Generos g ON pg.id_genero = g.id_genero
              LEFT JOIN Likes l_user ON p.id_pelicula = l_user.id_pelicula AND l_user.id_usuario = ?";

    $params = [$userId];
    $where = [];

    // Aplicar filtros si existen
    if (!empty($filtros['titulo'])) {
        $where[] = "p.titulo LIKE ?";
        $params[] = "%" . $filtros['titulo'] . "%";
    }

    if (!empty($filtros['categoria'])) {
        $where[] = "g.nombre = ?";
        $params[] = $filtros['categoria'];
    }

    if (!empty($filtros['director'])) {
        $where[] = "p.director = ?";
        $params[] = $filtros['director'];
    }

    if (!empty($filtros['user_likes'])) {
        if ($filtros['user_likes'] === 'con_likes') {
            $where[] = "l_user.id_like IS NOT NULL";
        } else if ($filtros['user_likes'] === 'sin_likes') {
            $where[] = "l_user.id_like IS NULL";
        }
    }

    // Añadir cláusulas WHERE si hay filtros
    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }

    // Agrupar por película para manejar múltiples géneros
    $query .= " GROUP BY p.id_pelicula";

    // Ordenar resultados
    if (!empty($filtros['likes_order'])) {
        $query .= " ORDER BY p.likes " . ($filtros['likes_order'] === 'desc' ? 'DESC' : 'ASC');
    } else {
        $query .= " ORDER BY p.titulo ASC";
    }

    error_log("Query final: " . $query); // Debug
    error_log("Parámetros: " . print_r($params, true)); // Debug

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Películas encontradas: " . count($result)); // Debug
        return $result;
    } catch (PDOException $e) {
        error_log("Error en getPeliculasOrdenadas: " . $e->getMessage());
        return [];
    }
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