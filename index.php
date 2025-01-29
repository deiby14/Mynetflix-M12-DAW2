<?php
require 'datos_pelicula.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-video"></i> Netflix</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Películas</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Top 5 Películas -->
    <div class="container mt-5">
        <h2 class="mb-4"><i class="fas fa-fire"></i> Top 5 Películas</h2>
        <div class="row justify-content-center">
            <?php foreach ($peliculas_top5 as $pelicula): ?>
                <div class="col-6 col-md-4 col-lg-2 mb-4 d-flex justify-content-center">
                    <div class="movie-card text-center">
                        <a href="detalle_pelicula.php?id=<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>">
                            <img src="./img/<?php echo htmlspecialchars($pelicula['poster_url']); ?>" class="img-fluid">
                        </a>
                        <h5 class="movie-title"><?php echo htmlspecialchars($pelicula['titulo']); ?></h5>
                        <button class="like-btn" onclick="likePelicula(<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>)">
                            <i class="fas fa-thumbs-up"></i> Like
                        </button>
                        <div class="like-count" id="likes-<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>">
                            <?php echo htmlspecialchars($pelicula['likes']); ?> Likes
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Todas las Películas -->
    <div class="container mt-5">
        <h2 class="mb-4"><i class="fas fa-film"></i> Todas las Películas</h2>
        <div class="row">
            <?php foreach ($todas_peliculas as $pelicula): ?>
                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <div class="movie-card text-center">
                        <a href="detalle_pelicula.php?id=<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>">
                            <img src="./img/<?php echo htmlspecialchars($pelicula['poster_url']); ?>" class="img-fluid">
                        </a>
                        <h5 class="movie-title"><?php echo htmlspecialchars($pelicula['titulo']); ?></h5>
                        <button class="like-btn" onclick="likePelicula(<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>)">
                            <i class="fas fa-thumbs-up"></i> Like
                        </button>
                        <div class="like-count" id="likes-<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>">
                            <?php echo htmlspecialchars($pelicula['likes']); ?> Likes
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>