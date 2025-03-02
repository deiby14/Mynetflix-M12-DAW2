<?php
require_once 'includes/peliculas_functions.php';

$id_pelicula = $_GET['id'];
$pelicula = getPeliculaById($id_pelicula);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Detalle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #141414;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: #000;
        }
        .navbar-brand i {
            color: #e50914;
        }
        .movie-details {
            margin-top: 2rem;
            padding: 2rem;
            background: rgba(20,20,20,0.9);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.8);
        }
        .movie-poster {
            width: 100%;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .movie-poster:hover {
            transform: scale(1.05);
        }
        .btn-netflix {
            background-color: #e50914;
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-netflix:hover {
            background-color: #b81d24;
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .movie-info p {
            font-size: 1.1rem;
        }
        .movie-info p strong {
            color: #e50914;
        }
        @media (max-width: 767.98px) {
            .movie-details {
                padding: 1rem;
            }
            h1 {
                font-size: 2rem;
            }
        }
    </style>
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
                    <div class="col-md-5 mb-3 mb-md-0">
                        <img src="./img/<?php echo htmlspecialchars($pelicula['poster_url']); ?>" 
                             alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>" 
                             class="movie-poster img-fluid">
                    </div>
                    <div class="col-md-7 movie-info">
                        <h1><?php echo htmlspecialchars($pelicula['titulo']); ?></h1>
                        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($pelicula['descripcion']); ?></p>
                        <p><strong>Géneros:</strong> <?php echo htmlspecialchars(str_replace(',', ', ', $pelicula['generos'])); ?></p>
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
