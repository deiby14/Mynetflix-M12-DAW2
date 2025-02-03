<?php
include 'conexion.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Procesar formulario de añadir/editar
        if ($_POST['accion'] === 'añadir') {
            $stmt = $conn->prepare("INSERT INTO Peliculas (titulo, director, fecha_estreno, categoria, likes) VALUES (?, ?, ?, ?, 0)");
            $stmt->execute([
                $_POST['titulo'],
                $_POST['director'],
                $_POST['fecha_estreno'],
                $_POST['categoria']
            ]);
        } 
        elseif ($_POST['accion'] === 'editar') {
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
