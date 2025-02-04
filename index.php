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
                        <button class="like-btn <?php echo $pelicula['user_liked'] ? 'liked' : ''; ?>"
                                data-pelicula-id="<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>"
                                <?php echo !$isLoggedIn ? 'disabled' : ''; ?>>
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

    <!-- Filtros (solo para usuarios autenticados) -->
    <?php if (isset($_SESSION['usuario'])): ?>
        <div class="container mt-5 mb-4">
            <div class="filter-container bg-dark p-3 rounded">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="filterTitulo" placeholder="Buscar por título...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="filterCategoria">
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
                            <option value="documental">Documental</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="filterDirector">
                            <option value="">Todos los directores</option>
                            <?php foreach (getDirectores() as $director): ?>
                                <option value="<?php echo htmlspecialchars($director); ?>">
                                    <?php echo htmlspecialchars($director); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="filterLikes">
                            <option value="">Ordenar por likes</option>
                            <option value="asc">Menos populares</option>
                            <option value="desc">Más populares</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="filterUserLikes">
                            <option value="">Todos</option>
                            <option value="liked">Mis likes</option>
                            <option value="not_liked">Sin like</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-secondary w-100" id="resetFilters">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Contenedor para todas las películas -->
    <div class="container mt-5">
        <h2 class="mb-4"><i class="fas fa-film"></i> Todas las Películas</h2>
        <div class="row" id="todasPeliculas">
            <?php if (!isset($_SESSION['usuario'])): ?>
                <?php
                // Cargar películas directamente para usuarios no autenticados
                $peliculas = getAllPeliculas();
                foreach ($peliculas as $pelicula): ?>
                    <div class="col-6 col-md-4 col-lg-2 mb-4">
                        <div class="movie-card text-center">
                            <a href="detalle_pelicula.php?id=<?php echo htmlspecialchars($pelicula['id_pelicula']); ?>">
                                <img src="./img/<?php echo htmlspecialchars($pelicula['poster_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>"
                                     class="img-fluid">
                            </a>
                            <h5 class="movie-title"><?php echo htmlspecialchars($pelicula['titulo']); ?></h5>
                            <button class="like-btn disabled" disabled>
                                <i class="fas fa-thumbs-up"></i> Like
                            </button>
                            <div class="like-count">
                                <?php echo htmlspecialchars($pelicula['likes']); ?> Likes
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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