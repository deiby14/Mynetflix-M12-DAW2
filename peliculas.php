<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Consultar las películas de la base de datos
try {
    $stmt = $pdo->query("SELECT * FROM Peliculas ORDER BY likes DESC");
    $peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener las películas: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Películas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
        }
        th {
            background-color:rgb(255, 233, 233);
        }
    </style>
</head>
<body>
    <h1>Catálogo de Películas</h1>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Director</th>
                <th>Fecha de Estreno</th>
                <th>Categoría</th>
                <th>Likes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($peliculas as $pelicula): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pelicula['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($pelicula['director']); ?></td>
                    <td><?php echo htmlspecialchars($pelicula['fecha_estreno']); ?></td>
                    <td><?php echo htmlspecialchars($pelicula['categoria']); ?></td>
                    <td><?php echo htmlspecialchars($pelicula['likes']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
