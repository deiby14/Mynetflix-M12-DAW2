<?php
require_once 'conexion.php';

function subirImagen($archivo) {
    $directorio_destino = "img/";
    $nombre_archivo = uniqid() . "_" . basename($archivo["name"]);
    $ruta_destino = $directorio_destino . $nombre_archivo;
    
    // Verificar tipo de archivo
    $tipo_archivo = strtolower(pathinfo($ruta_destino, PATHINFO_EXTENSION));
    if($tipo_archivo != "jpg" && $tipo_archivo != "jpeg" && $tipo_archivo != "png") {
        throw new Exception("Solo se permiten archivos JPG, JPEG y PNG.");
    }
    
    // Verificar tamaño (2MB máximo)
    if ($archivo["size"] > 2000000) {
        throw new Exception("El archivo es demasiado grande. Máximo 2MB.");
    }
    
    if (move_uploaded_file($archivo["tmp_name"], $ruta_destino)) {
        return $nombre_archivo;
    } else {
        throw new Exception("Error al subir el archivo.");
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Añadir película
        if ($_POST['accion'] === 'añadir') {
            $conn->beginTransaction();

            // Verificar que se hayan seleccionado categorías
            if (!isset($_POST['categorias']) || empty($_POST['categorias'])) {
                throw new Exception("Debes seleccionar al menos una categoría.");
            }

            // Procesar la imagen
            $poster_url = "default.jpg";
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                $poster_url = subirImagen($_FILES['poster']);
            }

            // Insertar la película
            $stmt = $conn->prepare("INSERT INTO Peliculas (titulo, director, fecha_estreno, poster_url, likes) 
                                  VALUES (?, ?, ?, ?, 0)");
            $stmt->execute([
                $_POST['titulo'],
                $_POST['director'],
                $_POST['fecha_estreno'],
                $poster_url
            ]);

            $id_pelicula = $conn->lastInsertId();

            // Procesar categorías
            foreach ($_POST['categorias'] as $categoria) {
                // Verificar si la categoría existe
                $stmt = $conn->prepare("SELECT id_genero FROM Generos WHERE nombre = ?");
                $stmt->execute([$categoria]);
                $genero = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$genero) {
                    // Crear nuevo género si no existe
                    $stmt = $conn->prepare("INSERT INTO Generos (nombre) VALUES (?)");
                    $stmt->execute([$categoria]);
                    $id_genero = $conn->lastInsertId();
                } else {
                    $id_genero = $genero['id_genero'];
                }

                // Asociar película con género
                $stmt = $conn->prepare("INSERT INTO Peliculas_Generos (id_pelicula, id_genero) VALUES (?, ?)");
                $stmt->execute([$id_pelicula, $id_genero]);
            }

            $conn->commit();
            header("Location: peliculas.php?mensaje=Película añadida correctamente");
            exit();
        }
        
        // Editar película
        elseif ($_POST['accion'] === 'editar') {
            $conn->beginTransaction();

            // Verificar que se hayan seleccionado categorías
            if (!isset($_POST['categorias']) || empty($_POST['categorias'])) {
                throw new Exception("Debes seleccionar al menos una categoría.");
            }

            $id_pelicula = $_POST['id'];
            $poster_actual = $_POST['poster_actual'] ?? 'default.jpg';

            // Procesar la imagen
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                if ($poster_actual !== 'default.jpg' && file_exists("img/" . $poster_actual)) {
                    unlink("img/" . $poster_actual);
                }
                $poster_url = subirImagen($_FILES['poster']);
            } else {
                $poster_url = $poster_actual;
            }

            // Actualizar la película
            $stmt = $conn->prepare("UPDATE Peliculas SET 
                                  titulo = ?, 
                                  director = ?, 
                                  fecha_estreno = ?,
                                  poster_url = ?
                                  WHERE id_pelicula = ?");
            $stmt->execute([
                $_POST['titulo'],
                $_POST['director'],
                $_POST['fecha_estreno'],
                $poster_url,
                $id_pelicula
            ]);

            // Eliminar categorías anteriores
            $stmt = $conn->prepare("DELETE FROM Peliculas_Generos WHERE id_pelicula = ?");
            $stmt->execute([$id_pelicula]);

            // Procesar nuevas categorías
            foreach ($_POST['categorias'] as $categoria) {
                // Verificar si la categoría existe
                $stmt = $conn->prepare("SELECT id_genero FROM Generos WHERE nombre = ?");
                $stmt->execute([$categoria]);
                $genero = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$genero) {
                    // Crear nuevo género si no existe
                    $stmt = $conn->prepare("INSERT INTO Generos (nombre) VALUES (?)");
                    $stmt->execute([$categoria]);
                    $id_genero = $conn->lastInsertId();
                } else {
                    $id_genero = $genero['id_genero'];
                }

                // Asociar película con género
                $stmt = $conn->prepare("INSERT INTO Peliculas_Generos (id_pelicula, id_genero) VALUES (?, ?)");
                $stmt->execute([$id_pelicula, $id_genero]);
            }

            $conn->commit();
            header("Location: peliculas.php?mensaje=Película actualizada correctamente");
            exit();
        }
    }
    
    // Eliminar película
    elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
        $conn->beginTransaction();

        $id_pelicula = $_GET['id'];

        // Primero obtener información de la película
        $stmt = $conn->prepare("SELECT poster_url FROM Peliculas WHERE id_pelicula = ?");
        $stmt->execute([$id_pelicula]);
        $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

        // Eliminar primero las relaciones en peliculas_generos
        $stmt = $conn->prepare("DELETE FROM Peliculas_Generos WHERE id_pelicula = ?");
        $stmt->execute([$id_pelicula]);

        // Luego eliminar la película
        $stmt = $conn->prepare("DELETE FROM Peliculas WHERE id_pelicula = ?");
        $stmt->execute([$id_pelicula]);

        // Finalmente eliminar la imagen si existe
        if ($pelicula && $pelicula['poster_url'] !== 'default.jpg' && file_exists("img/" . $pelicula['poster_url'])) {
            unlink("img/" . $pelicula['poster_url']);
        }

        $conn->commit();
        header("Location: peliculas.php?mensaje=Película eliminada correctamente");
        exit();
    }

} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollBack();
    }
    header("Location: peliculas.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>
