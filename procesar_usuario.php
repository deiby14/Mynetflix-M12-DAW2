<?php
include 'conexion.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Procesar formulario de añadir/editar
        if ($_POST['accion'] === 'añadir') {
            $stmt = $conn->prepare("INSERT INTO Usuarios (nombre, email, contrasena, estado, es_admin) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['nombre'],
                $_POST['email'],
                password_hash($_POST['contrasena'], PASSWORD_DEFAULT),
                $_POST['estado'],
                $_POST['es_admin']
            ]);
        } 
        elseif ($_POST['accion'] === 'editar') {
            $stmt = $conn->prepare("UPDATE Usuarios SET nombre = ?, email = ?, estado = ?, es_admin = ? WHERE id_usuario = ?");
            $stmt->execute([
                $_POST['nombre'],
                $_POST['email'],
                $_POST['estado'],
                $_POST['es_admin'],
                $_POST['id']
            ]);
        }
    }
    elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
        // Procesar eliminación
        $stmt = $conn->prepare("DELETE FROM Usuarios WHERE id_usuario = ?");
        $stmt->execute([$_GET['id']]);
    }

    // Redireccionar de vuelta a la página principal
    header('Location: usuarios.php');
    exit();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?> 