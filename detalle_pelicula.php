<?php
require 'conexion.php';


    $id_pelicula = $_GET['id'];

    // Consulta para obtener los detalles de la película
    $query = "SELECT * FROM Peliculas WHERE id_pelicula = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id_pelicula, PDO::PARAM_INT);
    $stmt->execute();
    $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Detalle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/detalle.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-video"></i> Netflix</a>
        </div>
    </nav>

    <!-- Movie Details -->
    <div class="container">
        <?php if ($pelicula): ?>
            <div class="movie-details">
                <div class="row">
                    <div class="col-md-5">
                        <img src="./img/<?php echo htmlspecialchars($pelicula['poster_url']); ?>" 
                             alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>" 
                             class="movie-poster">
                    </div>
                    <div class="col-md-7">
                        <h1><?php echo htmlspecialchars($pelicula['titulo']); ?></h1>
                        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($pelicula['descripcion']); ?></p>
                        <p><strong>Categoría:</strong> <?php echo htmlspecialchars($pelicula['categoria']); ?></p>
                        <p><strong>Director:</strong> <?php echo htmlspecialchars($pelicula['director']); ?></p>
                        <p><strong>Likes:</strong> <?php echo htmlspecialchars($pelicula['likes']); ?></p>
                        <a href="index.php" class="btn-netflix">Volver</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger text-center mt-5">
                Película no encontrada.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
