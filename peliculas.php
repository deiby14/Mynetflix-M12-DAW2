<?php
// Incluir el archivo de conexión
include 'conexion.php';

try {
    // Consulta para obtener todas las películas
    $stmt = $conn->query("SELECT id_pelicula, titulo, director, fecha_estreno, categoria, likes FROM Peliculas ORDER BY titulo ASC");
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
        <a href="?accion=mostrar_form" class="btn btn-primary mb-3">Añadir Película</a>
        
        <?php 
        if(isset($_GET['accion']) && $_GET['accion'] == 'mostrar_form'): 
        ?>
            <form action="procesar_pelicula.php" method="POST" class="mb-4">
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
                    <label for="categoria">Categoría</label>
                    <select class="form-control" id="categoria" name="categoria" required>
                        <option value="">Seleccione una categoría</option>
                        <option value="Acción">Acción</option>
                        <option value="Comedia">Comedia</option>
                        <option value="Drama">Drama</option>
                        <option value="Terror">Terror</option>
                    </select>
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
            <form action="procesar_pelicula.php" method="POST" class="mb-4">
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
                    <label for="categoria">Categoría</label>
                    <select class="form-control" id="categoria" name="categoria" required>
                        <option value="Acción" <?= $pelicula['categoria'] == 'Acción' ? 'selected' : '' ?>>Acción</option>
                        <option value="Comedia" <?= $pelicula['categoria'] == 'Comedia' ? 'selected' : '' ?>>Comedia</option>
                        <option value="Drama" <?= $pelicula['categoria'] == 'Drama' ? 'selected' : '' ?>>Drama</option>
                        <option value="Terror" <?= $pelicula['categoria'] == 'Terror' ? 'selected' : '' ?>>Terror</option>
                    </select>
                </div>
                
                <button type="submit" name="accion" value="editar" class="btn btn-primary">Guardar</button>
                <a href="peliculas.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>

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
    </div>
</body>
</html>
