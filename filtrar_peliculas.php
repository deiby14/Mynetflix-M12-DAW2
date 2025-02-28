<?php
    require_once 'conexion.php';  // Ya no buscará en includes/

// Obtener los parámetros de filtrado
$titulo = $_POST['titulo'] ?? '';
$director = $_POST['director'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$orden = $_POST['orden'] ?? 'DESC';

// Construir la consulta SQL base
$sql = "SELECT p.*, GROUP_CONCAT(g.nombre) as generos 
        FROM Peliculas p
        LEFT JOIN Peliculas_Generos pg ON p.id_pelicula = pg.id_pelicula
        LEFT JOIN Generos g ON pg.id_genero = g.id_genero
        WHERE 1=1";
$params = [];

// Añadir condiciones según los filtros
if (!empty($titulo)) {
    $sql .= " AND p.titulo LIKE ?";
    $params[] = "%$titulo%";
}

if (!empty($director)) {
    $sql .= " AND p.director LIKE ?";
    $params[] = "%$director%";
}

if (!empty($categoria)) {
    $sql .= " AND g.nombre = ?";
    $params[] = $categoria;
}

if (!empty($fecha)) {
    $sql .= " AND YEAR(p.fecha_estreno) = ?";
    $params[] = $fecha;
}

// Agrupar por película y ordenar
$sql .= " GROUP BY p.id_pelicula ORDER BY p.likes " . ($orden === 'ASC' ? 'ASC' : 'DESC');

// Debug: Ver la consulta SQL final
error_log('SQL Query: ' . $sql);
error_log('Parameters: ' . print_r($params, true));

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: Ver resultados
    error_log('Número de resultados: ' . count($peliculas));

    // Devolver los resultados como JSON
    header('Content-Type: application/json');
    echo json_encode($peliculas);
} catch (PDOException $e) {
    error_log('Error en la consulta: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al filtrar las películas: ' . $e->getMessage()]);
}
?> 