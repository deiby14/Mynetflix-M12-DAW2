<?php
include 'conexion.php';

function procesarImagen($archivo) {
    $directorio_destino = "img/";
    $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
    $nombre_archivo = uniqid() . "." . $extension;
    $ruta_destino = $directorio_destino . $nombre_archivo;

    // Verificar el tipo de archivo
    $permitidos = array("jpg", "jpeg", "png");
    if (!in_array($extension, $permitidos)) {
        throw new Exception("Solo se permiten archivos JPG, JPEG y PNG.");
    }

    // Verificar el tamaño (2MB máximo)
    if ($archivo["size"] > 2 * 1024 * 1024) {
        throw new Exception("El archivo es demasiado grande. Máximo 2MB.");
    }

    // Mover el archivo
    if (!move_uploaded_file($archivo["tmp_name"], $ruta_destino)) {
        throw new Exception("Error al subir el archivo.");
    }

    return $nombre_archivo;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Procesar formulario de añadir/editar
        if ($_POST['accion'] === 'añadir') {
            $poster_url = "default.jpg"; // Valor por defecto
            if (isset($_FILES["poster"]) && $_FILES["poster"]["error"] == 0) {
                $poster_url = procesarImagen($_FILES["poster"]);
            }

            $stmt = $conn->prepare("INSERT INTO Peliculas (titulo, director, fecha_estreno, categoria, poster_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['titulo'],
                $_POST['director'],
                $_POST['fecha_estreno'],
                $_POST['categoria'],
                $poster_url
            ]);
        } 
        elseif ($_POST['accion'] === 'editar') {
            $poster_url = null;
            if (isset($_FILES["poster"]) && $_FILES["poster"]["error"] == 0) {
                $poster_url = procesarImagen($_FILES["poster"]);
                
                // Eliminar la imagen anterior si existe y no es la default
                $stmt = $conn->prepare("SELECT poster_url FROM Peliculas WHERE id_pelicula = ?");
                $stmt->execute([$_POST['id']]);
                $old_poster = $stmt->fetchColumn();
                if ($old_poster && $old_poster != "default.jpg" && file_exists("img/" . $old_poster)) {
                    unlink("img/" . $old_poster);
                }
            }

            if ($poster_url) {
                $stmt = $conn->prepare("UPDATE Peliculas SET titulo = ?, director = ?, fecha_estreno = ?, categoria = ?, poster_url = ? WHERE id_pelicula = ?");
                $stmt->execute([
                    $_POST['titulo'],
                    $_POST['director'],
                    $_POST['fecha_estreno'],
                    $_POST['categoria'],
                    $poster_url,
                    $_POST['id']
                ]);
            } else {
                $stmt = $conn->prepare("UPDATE Peliculas SET titulo = ?, director = ?, fecha_estreno = ?, categoria = ? WHERE id_pelicula = ?");
                $stmt->execute([
                    $_POST['titulo'],
                    $_POST['director'],
                    $_POST['fecha_estreno'],
                    $_POST['categoria'],
                    $_POST['id']
                ]);
            }
        }
    }
    elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
        // Procesar eliminación
        $stmt = $conn->prepare("DELETE FROM Peliculas WHERE id_pelicula = ?");
        $stmt->execute([$_GET['id']]);
    }

    // Redireccionar de vuelta a la página principal
    header('Location: peliculas.php');
    exit();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
