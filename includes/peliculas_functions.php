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

function getTop5Peliculas($userId) {
    global $conn;
    
    $query = "SELECT p.*, 
              CASE WHEN l_user.id_like IS NOT NULL THEN 1 ELSE 0 END as user_liked,
              COUNT(l.id_like) as likes
              FROM Peliculas p
              LEFT JOIN Likes l_user ON p.id_pelicula = l_user.id_pelicula AND l_user.id_usuario = ?
              LEFT JOIN Likes l ON p.id_pelicula = l.id_pelicula
              GROUP BY p.id_pelicula
              ORDER BY likes DESC, p.titulo ASC
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

function getPeliculasOrdenadas($userId, $filtros = []) {
    global $conn;
    
    $where_clauses = [];
    $params = [];
    
    // Añadir userId a los parámetros iniciales
    $params[] = $userId;

    // Consulta base con conteo de likes
    $baseQuery = "SELECT p.*, 
                  CASE WHEN l_user.id_like IS NOT NULL THEN 1 ELSE 0 END as user_liked,
                  (SELECT COUNT(*) FROM Likes l WHERE l.id_pelicula = p.id_pelicula) as likes";

    // Filtro de likes del usuario
    if (!empty($filtros['user_likes'])) {
        if ($filtros['user_likes'] === 'con_likes') {
            // Películas que el usuario ha dado like
            $query = "SELECT p.*, 
                     1 as user_liked,
                     (SELECT COUNT(*) FROM Likes l WHERE l.id_pelicula = p.id_pelicula) as likes
                     FROM Peliculas p
                     INNER JOIN Likes l_user ON p.id_pelicula = l_user.id_pelicula AND l_user.id_usuario = ?";
        } else if ($filtros['user_likes'] === 'sin_likes') {
            // Películas que el usuario NO ha dado like
            $query = "SELECT p.*, 
                     0 as user_liked,
                     (SELECT COUNT(*) FROM Likes l WHERE l.id_pelicula = p.id_pelicula) as likes
                     FROM Peliculas p
                     WHERE p.id_pelicula NOT IN (
                         SELECT id_pelicula FROM Likes WHERE id_usuario = ?
                     )";
        } else {
            // Todas las películas (valor por defecto)
            $query = $baseQuery . " FROM Peliculas p
                     LEFT JOIN Likes l_user ON p.id_pelicula = l_user.id_pelicula AND l_user.id_usuario = ?";
        }
    } else {
        $query = $baseQuery . " FROM Peliculas p
                 LEFT JOIN Likes l_user ON p.id_pelicula = l_user.id_pelicula AND l_user.id_usuario = ?";
    }

    // Filtro de título
    if (!empty($filtros['titulo'])) {
        $where_clauses[] = "p.titulo LIKE ?";
        $params[] = "%" . $filtros['titulo'] . "%";
    }
    
    // Filtro de categoría
    if (!empty($filtros['categoria']) && $filtros['categoria'] !== 'Todas') {
        $where_clauses[] = "p.categoria = ?";
        $params[] = $filtros['categoria'];
    }
    
    // Filtro de director
    if (!empty($filtros['director']) && $filtros['director'] !== 'Todos') {
        $where_clauses[] = "p.director = ?";
        $params[] = $filtros['director'];
    }

    // Añadir cláusulas WHERE si existen
    if (!empty($where_clauses)) {
        $query .= (strpos($query, 'WHERE') !== false ? ' AND ' : ' WHERE ') . implode(" AND ", $where_clauses);
    }

    // Ordenar según el filtro de likes
    if (!empty($filtros['likes_order'])) {
        if ($filtros['likes_order'] === 'asc') {
            $query .= " ORDER BY likes ASC, p.titulo ASC";
        } else if ($filtros['likes_order'] === 'desc') {
            $query .= " ORDER BY likes DESC, p.titulo ASC";
        }
    } else {
        $query .= " ORDER BY p.titulo ASC";
    }

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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