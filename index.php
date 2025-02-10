<?php
require 'datos_pelicula.php';
require_once 'includes/session_check.php';
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
    <style>
        .like-btn.disabled {
            background-color: #ccc;
            cursor: not-allowed;
            opacity: 0.7;
            position: relative;
        }

        .like-btn.disabled::before {
            position: absolute;
            color: red;
            font-size: 1.2em;
        }
    </style>
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
                    <?php if (!$isLoggedIn): ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php else: ?>
                        <li class="nav-item"><span class="nav-link">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></span></li>
                        <li class="nav-item"><a class="nav-link" href="cerrar_sesion.php">Cerrar Sesión</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Top 5 Películas -->
    <div class="container mt-4">
        <h2><i class="fas fa-star"></i> Top 5 Películas</h2>
        <div class="row justify-content-center">
            <?php foreach ($peliculas_top5 as $pelicula): ?>
                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <div class="movie-card text-center">
                        <a href="detalle_pelicula.php?id=<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>">
                            <img src="./img/<?php echo htmlspecialchars($pelicula['poster_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>"
                                 class="img-fluid">
                        </a>
                        <h5 class="movie-title"><?php echo htmlspecialchars($pelicula['titulo']); ?></h5>
                        <p class="movie-genres"><?php echo htmlspecialchars(str_replace(',', ', ', $pelicula['generos'])); ?></p>
                        <button class="like-btn <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ($pelicula['user_liked'] ? 'liked' : ''); ?>"
                                data-pelicula-id="<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>"
                                <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>>
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
    <div class="container mt-4">
        <h2><i class="fas fa-film"></i> Todas las Películas</h2>
        
        <!-- Filtros (siempre visibles, pero deshabilitados si no hay sesión) -->
        <div class="row mb-4">
            <div class="col-md-2 mb-2">
                <input type="text" id="filterTitulo" class="form-control" 
                       placeholder="Buscar por título..."
                       <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>>
            </div>
            <div class="col-md-2 mb-2">
                <select id="filterCategoria" class="form-select"
                        <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>>
                    <option value="">Todas las categorías</option>
                    <option value="accion">Acción</option>
                    <option value="aventura">Aventura</option>
                    <option value="comedia">Comedia</option>
                    <option value="drama">Drama</option>
                    <option value="terror">Terror</option>
                    <option value="suspenso">Suspenso</option>
                    <option value="ciencia_ficcion">Ciencia Ficción</option>
                    <option value="fantasia">Fantasía</option>
                    <option value="musical">Musical</option>
                    <option value="animacion">Animación</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <select id="filterDirector" class="form-select"
                        <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>>
                    <option value="">Todos los directores</option>
                    <?php 
                    $directores = getDirectores();
                    foreach ($directores as $director): ?>
                        <option value="<?php echo htmlspecialchars($director); ?>">
                            <?php echo htmlspecialchars($director); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <select id="filterLikes" class="form-select"
                        <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>>
                    <option value="">Ordenar por likes</option>
                    <option value="desc">Más likes primero</option>
                    <option value="asc">Menos likes primero</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <select id="filterUserLikes" class="form-select"
                        <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>>
                    <option value="">Todos</option>
                    <option value="con_likes">Con mis likes</option>
                    <option value="sin_likes">Sin mis likes</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <button id="resetFilters" class="btn btn-secondary w-100"
                        <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>>
                    <i class="fas fa-sync-alt"></i> Reiniciar
                </button>
            </div>
        </div>

        <!-- Contenedor de películas -->
        <div class="row" id="todasPeliculas">
            <?php foreach ($todas_peliculas as $pelicula): ?>
                <div class="col-6 col-md-4 col-lg-2 mb-4">
                    <div class="movie-card text-center">
                        <a href="detalle_pelicula.php?id=<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>">
                            <img src="./img/<?php echo htmlspecialchars($pelicula['poster_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>"
                                 class="img-fluid">
                        </a>
                        <h5 class="movie-title"><?php echo htmlspecialchars($pelicula['titulo']); ?></h5>
                        <p class="movie-genres"><?php echo htmlspecialchars(str_replace(',', ', ', $pelicula['generos'])); ?></p>
                        <button class="like-btn <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ($pelicula['user_liked'] ? 'liked' : ''); ?>"
                                data-pelicula-id="<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>"
                                <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>>
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

    <script src="js/likes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        const userIsAuthenticated = <?php echo isset($_SESSION['usuario']) ? 'true' : 'false'; ?>;
        console.log('Estado de autenticación:', userIsAuthenticated); // Debug
    </script>
    <script src="js/filtros.js"></script>
</body>

</html>