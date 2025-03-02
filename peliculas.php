<?php
    require_once 'conexion.php';  // Ya no buscará en includes/

try {
    $stmt = $conn->query("SELECT p.*, GROUP_CONCAT(g.nombre) as generos FROM Peliculas p
                           LEFT JOIN Peliculas_Generos pg ON p.id_pelicula = pg.id_pelicula
                           LEFT JOIN Generos g ON pg.id_genero = g.id_genero
                           GROUP BY p.id_pelicula
                           ORDER BY p.likes DESC");
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
    <title>Gestión de Películas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://stackpath.  bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <h1 class="mb-4">Gestión de Películas</h1>
        
        <!-- Filtros -->
        <div class="card bg-dark mb-4">
            <div class="card-body">
                <h5 class="card-title text-white">Filtros de búsqueda</h5>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <input type="text" id="busqueda_titulo" class="form-control" placeholder="Buscar por título...">
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" id="busqueda_director" class="form-control" placeholder="Buscar por director...">
                    </div>
                    <div class="col-md-2 mb-2">
                        <select id="filtro_categoria" class="form-control">
                            <option value="">Todas las categorías</option>
                            <option value="accion">Acción</option>
                            <option value="aventura">Aventura</option>
                            <option value="comedia">Comedia</option>
                            <option value="drama">Drama</option>
                            <option value="terror">Terror</option>
                            <option value="suspenso">Suspenso</option>
                            <option value="cienciaficcion">Ciencia Ficción</option>
                            <option value="fantasia">Fantasía</option>
                            <option value="musical">Musical</option>
                            <option value="animacion">Animación</option>
                            <option value="documental">Documental</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select id="filtro_fecha" class="form-control">
                            <option value="">Todos los años</option>
                            <?php 
                            $año_actual = date('Y');
                            $año_mas_antigua = 1900;
                            for($año = $año_actual; $año >= $año_mas_antigua; $año--) {
                                echo "<option value='$año'>$año</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select id="orden_likes" class="form-control">
                            <option value="DESC">Más likes primero</option>
                            <option value="ASC">Menos likes primero</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <a href="?accion=mostrar_form" class="btn btn-primary mb-3">Añadir Película</a>
        
        <?php 
        if(isset($_GET['accion']) && $_GET['accion'] == 'mostrar_form'): 
        ?>
            <form action="procesar_pelicula.php" method="POST" class="mb-4" enctype="multipart/form-data">
                <h3>Añadir Película</h3>
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>
                
                <div class="form-group">
                    <label for="director">Director</label>
                    <input type="text" class="form-control" id="director" name="director" required>
                </div>
                
                <div class="form-group">
                    <label for="fecha_estreno">Fecha de Estreno</label>
                    <input type="date" class="form-control" id="fecha_estreno" name="fecha_estreno" required>
                </div>
                
                <div class="form-group">
                    <label for="categorias">Categorías</label>
                    <select multiple class="form-control" id="categorias" name="categorias[]" required>
                        <option value="">Selecciona las categorías</option>
                        <?php
                        // Obtener todas las categorías disponibles
                        $stmt = $conn->query("SELECT DISTINCT nombre FROM Generos ORDER BY nombre");
                        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($categorias as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria['nombre']) ?>">
                                <?= htmlspecialchars($categoria['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted">Puedes buscar y seleccionar múltiples categorías</small>
                </div>
                
                <div class="form-group">
                    <label for="poster">Imagen de la película</label>
                    <input type="file" class="form-control" id="poster" name="poster" accept="image/*">
                    <small class="form-text text-muted">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 2MB</small>
                </div>
                
                <button type="submit" name="accion" value="añadir" class="btn btn-primary">Guardar</button>
                <a href="peliculas.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php 
        elseif(isset($_GET['accion']) && $_GET['accion'] == 'editar' && isset($_GET['id'])): 
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM Peliculas WHERE id_pelicula = ?");
            $stmt->execute([$id]);
            $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
            <form action="procesar_pelicula.php" method="POST" class="mb-4" enctype="multipart/form-data">
                <h3>Editar Película</h3>
                <input type="hidden" name="id" value="<?= htmlspecialchars($pelicula['id_pelicula']) ?>">
                
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" 
                           value="<?= htmlspecialchars($pelicula['titulo']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="director">Director</label>
                    <input type="text" class="form-control" id="director" name="director" 
                           value="<?= htmlspecialchars($pelicula['director']) ?>" required>

                </div>
                
                <div class="form-group">
                    <label for="fecha_estreno">Fecha de Estreno</label>
                    <input type="date" class="form-control" id="fecha_estreno" name="fecha_estreno" 
                           value="<?= htmlspecialchars($pelicula['fecha_estreno']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="categorias">Categorías</label>
                    <select multiple class="form-control" id="categorias" name="categorias[]" required>
                        <?php
                        // Obtener todas las categorías disponibles
                        $stmt = $conn->query("SELECT DISTINCT nombre FROM Generos ORDER BY nombre");
                        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        // Obtener las categorías actuales de la película
                        $stmt = $conn->prepare("SELECT g.nombre 
                                               FROM Generos g 
                                               JOIN Peliculas_Generos pg ON g.id_genero = pg.id_genero 
                                               WHERE pg.id_pelicula = ?");
                        $stmt->execute([$id]);
                        $categorias_actuales = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        
                        foreach ($categorias as $categoria): ?>
                            <option value="<?= htmlspecialchars($categoria['nombre']) ?>" 
                                    <?= in_array($categoria['nombre'], $categorias_actuales) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted">Puedes buscar y seleccionar múltiples categorías</small>
                </div>
                
                <div class="form-group">
                    <label for="poster">Imagen de la película</label>
                    <input type="file" class="form-control" id="poster" name="poster" accept="image/*">
                    <small class="form-text text-muted">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 2MB</small>
                    <?php if (isset($pelicula) && $pelicula['poster_url'] != "default.jpg"): ?>
                        <img src="img/<?= htmlspecialchars($pelicula['poster_url']) ?>" alt="Poster actual" style="max-width: 200px; margin-top: 10px;">
                    <?php endif; ?>
                </div>
                
                <button type="submit" name="accion" value="editar" class="btn btn-primary">Guardar</button>
                <a href="peliculas.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>

        <!-- Tabla de películas con ID para AJAX -->
        <div id="tabla_peliculas">
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
                            <td><?= htmlspecialchars($pelicula['generos']) ?></td>
                            <td><?= htmlspecialchars($pelicula['likes']) ?></td>
                            <td>
                                <a href="?accion=editar&id=<?= $pelicula['id_pelicula'] ?>" 
                                   class="btn btn-warning btn-sm">Editar</a>
                                <a href="procesar_pelicula.php?accion=eliminar&id=<?= $pelicula['id_pelicula'] ?>" 
                                   class="btn btn-danger btn-sm btn-eliminar">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar la película "<span id="peliculaAEliminar"></span>"?</p>
                    <p class="text-danger mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">Eliminar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Añadir Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css" rel="stylesheet">
    <script src="js/filtrospeliculas.js"></script>

    <!-- Script para el modal y Select2 -->
    <script>
    $(document).ready(function() {
        // Inicializar Select2 para las categorías
        $('#categorias').select2({
            theme: 'bootstrap',
            placeholder: 'Selecciona las categorías',
            width: '100%',
            language: {
                noResults: function() {
                    return "No se encontraron resultados";
                }
            }
        });

        // Manejar el modal de eliminación
        $('.btn-eliminar').click(function(e) {
            e.preventDefault();
            var deleteUrl = $(this).attr('href');
            var titulo = $(this).closest('tr').find('td:first').text();
            $('#peliculaAEliminar').text(titulo);
            $('#btnConfirmarEliminar').attr('href', deleteUrl);
            $('#modalEliminar').modal('show');
        });
    });
    </script>

    <style>
    /* Estilos para Select2 */
    .select2-container--bootstrap .select2-selection {
        background-color: #2c3034;
        border: 1px solid #444;
        color: white;
    }

    .select2-container--bootstrap .select2-selection--multiple {
        min-height: 38px;
    }

    .select2-container--bootstrap .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #0056b3;
        color: #fff;
        padding: 2px 8px;
    }

    .select2-container--bootstrap .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 5px;
    }

    /* Estilos para el dropdown de Select2 */
    .select2-container--bootstrap .select2-dropdown {
        background-color: #2c3034;
        border: 1px solid #444;
    }

    .select2-container--bootstrap .select2-results__option {
        color: white;
        padding: 6px 12px;
    }

    .select2-container--bootstrap .select2-results__option[aria-selected=true] {
        background-color: #007bff;
        color: white;
    }

    .select2-container--bootstrap .select2-results__option--highlighted[aria-selected] {
        background-color: #0056b3;
        color: white;
    }

    .select2-search--dropdown .select2-search__field {
        background-color: #343a40;
        color: white;
        border: 1px solid #444;
    }

    .select2-container--bootstrap .select2-selection--multiple .select2-search--inline .select2-search__field {
        background-color: transparent;
        color: white;
    }

    /* Placeholder color */
    .select2-container--bootstrap .select2-selection--multiple .select2-search--inline .select2-search__field::placeholder {
        color: #aaa;
    }
    </style>
</body>
</html>