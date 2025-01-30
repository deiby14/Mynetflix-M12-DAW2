<?php
// Incluir el archivo de conexión
include 'conexion.php';

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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #141414;
            color: #fff;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4">
            <a href="administrador.php" class="btn btn-secondary">Volver</a>
            <a href="usuarios.php" class="btn btn-info">Ir a Usuarios</a>
        </div>
        <h1 class="mb-4">Catálogo de Películas</h1>
        <button class="btn btn-primary mb-3">Añadir Película</button>
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
                        <td><?php echo htmlspecialchars($pelicula['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($pelicula['director']); ?></td>
                        <td><?php echo htmlspecialchars($pelicula['fecha_estreno']); ?></td>
                        <td><?php echo htmlspecialchars($pelicula['categoria']); ?></td>
                        <td><?php echo htmlspecialchars($pelicula['likes']); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarPelicula(<?php echo $pelicula['id_pelicula']; ?>)">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarPelicula(<?php echo $pelicula['id_pelicula']; ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

   
</body>
</html>
