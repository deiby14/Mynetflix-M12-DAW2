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

function getGeneros() {
    global $conn;
    $stmt = $conn->query("SELECT nombre FROM Generos ORDER BY nombre");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getPeliculasOrdenadas($userId, $filtros = []) {
    global $conn;
    
    // Consulta base
    $query = "SELECT DISTINCT p.*,
              GROUP_CONCAT(DISTINCT g.nombre) as generos,
              CASE WHEN l.id_like IS NOT NULL THEN 1 ELSE 0 END as user_liked
              FROM Peliculas p
              LEFT JOIN Likes l ON p.id_pelicula = l.id_pelicula AND l.id_usuario = ?
              LEFT JOIN Peliculas_Generos pg ON p.id_pelicula = pg.id_pelicula
              LEFT JOIN Generos g ON pg.id_genero = g.id_genero";
    
    $params = [$userId];
    $where = [];
    
    // Filtrado por géneros - Añadida verificación de existencia
    if (isset($filtros['generos']) && !empty($filtros['generos'])) {
        $generosArray = array_filter(explode(',', $filtros['generos']));
        if (!empty($generosArray)) {
            $query .= " WHERE p.id_pelicula IN (
                SELECT pg1.id_pelicula
                FROM Peliculas_Generos pg1
                JOIN Generos g1 ON pg1.id_genero = g1.id_genero
                WHERE g1.nombre IN (" . str_repeat('?,', count($generosArray) - 1) . "?)
                GROUP BY pg1.id_pelicula
                HAVING COUNT(DISTINCT g1.nombre) = ?
            )";
            
            foreach ($generosArray as $genero) {
                $params[] = trim($genero);
            }
            $params[] = count($generosArray);
        }
    }

    // Resto de filtros con verificación
    if (isset($filtros['titulo']) && !empty($filtros['titulo'])) {
        $where[] = "p.titulo LIKE ?";
        $params[] = "%" . $filtros['titulo'] . "%";
    }

    if (isset($filtros['director']) && !empty($filtros['director'])) {
        $where[] = "p.director = ?";
        $params[] = $filtros['director'];
    }

    if (!empty($where)) {
        $query .= (strpos($query, 'WHERE') === false ? " WHERE " : " AND ") . implode(" AND ", $where);
    }

    $query .= " GROUP BY p.id_pelicula";

    if (isset($filtros['likes_order']) && !empty($filtros['likes_order'])) {
        $query .= " ORDER BY p.likes " . ($filtros['likes_order'] === 'desc' ? 'DESC' : 'ASC');
    } else {
        $query .= " ORDER BY p.titulo ASC";
    }

    // Debug
    error_log("Query ejecutada: " . $query);
    error_log("Parámetros: " . print_r($params, true));

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("Películas encontradas: " . count($result));
    return $result;
}

function getPeliculasFiltradas($filtros, $userId) {
    global $conn;
    
    $query = "SELECT p.*, 
              CASE WHEN l.id_like IS NOT NULL THEN 1 ELSE 0 END as user_liked
              FROM Peliculas p
              LEFT JOIN Likes l ON p.id_pelicula = l.id_pelicula AND l.id_usuario = ?";
    
    $params = [$userId];
    
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
    
    if (!empty($filtros['likes_order'])) {
        $query .= " ORDER BY p.likes " . ($filtros['likes_order'] === 'desc' ? 'DESC' : 'ASC');
    } else {
        $query .= " ORDER BY p.titulo ASC";
    }
    
    error_log("Query: " . $query);
    error_log("Params: " . print_r($params, true));
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("Resultados encontrados: " . count($result));
    return $result;
}
?> 