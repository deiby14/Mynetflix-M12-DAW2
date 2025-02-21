<?php
    require_once 'conexion.php';  // Ya no buscará en includes/

// Debug: Ver los datos recibidos
error_log('POST data: ' . print_r($_POST, true));

// Construir la consulta SQL base
$sql = "SELECT * FROM Peliculas WHERE 1=1";
$params = [];

// Aplicar filtros si existen
if (!empty($_POST['titulo'])) {
    $sql .= " AND titulo LIKE ?";
    $params[] = '%' . $_POST['titulo'] . '%';
}

if (!empty($_POST['director'])) {
    $sql .= " AND director LIKE ?";
    $params[] = '%' . $_POST['director'] . '%';
}

if (!empty($_POST['categoria'])) {
    $sql .= " AND categoria = ?";
    $params[] = $_POST['categoria'];
}

if (!empty($_POST['fecha'])) {
    $sql .= " AND YEAR(fecha_estreno) = ?";
    $params[] = $_POST['fecha'];
}

// Ordenar por likes usando CONVERT para asegurar ordenamiento numérico
$orden = isset($_POST['orden']) ? strtoupper($_POST['orden']) : 'DESC';
$sql .= " ORDER BY CONVERT(likes, SIGNED INTEGER) " . $orden;

// Debug: Ver la consulta SQL final
error_log('SQL Query: ' . $sql);
error_log('Parameters: ' . print_r($params, true));

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: Ver resultados
    error_log('Número de resultados: ' . count($peliculas));

    // Generar la tabla HTML
    ?>
    <table class="table table-dark table-hover">
        <thead>
            <tr>
                <th>Título</th>
                <th>Director</th>
                <th>Fecha de Estreno</th>
                <th>Categoría</th>
                <th>Likes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($peliculas as $pelicula): ?>
                <tr>
                    <td><?= htmlspecialchars($pelicula['titulo']) ?></td>
                    <td><?= htmlspecialchars($pelicula['director']) ?></td>
                    <td><?= htmlspecialchars($pelicula['fecha_estreno']) ?></td>
                    <td><?= htmlspecialchars($pelicula['categoria']) ?></td>
                    <td><?= htmlspecialchars($pelicula['likes']) ?></td>
                    <td>
                        <a href="?accion=editar&id=<?= $pelicula['id_pelicula'] ?>" 
                           class="btn btn-warning btn-sm">Editar</a>
                        <a href="procesar_pelicula.php?accion=eliminar&id=<?= $pelicula['id_pelicula'] ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('¿Estás seguro de que deseas eliminar esta película?')">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
} catch (PDOException $e) {
    error_log('Error en la consulta: ' . $e->getMessage());
    echo "Error al filtrar las películas: " . $e->getMessage();
}
?> 