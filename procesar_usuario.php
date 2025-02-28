<?php
require_once 'conexion.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn->beginTransaction();

        if ($_POST['accion'] === 'añadir') {
            // Validar datos
            if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['password'])) {
                throw new Exception("Todos los campos son obligatorios");
            }

            // Verificar si el email ya existe
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Usuarios WHERE email = ?");
            $stmt->execute([htmlspecialchars($_POST['email'])]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("El email ya está registrado");
            }

            // Hash de la contraseña
            $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Insertar usuario
            $stmt = $conn->prepare("INSERT INTO Usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                htmlspecialchars($_POST['nombre']),
                htmlspecialchars($_POST['email']),
                $password_hash,
                htmlspecialchars($_POST['rol'] ?? 'usuario')
            ]);

            $conn->commit();
            header("Location: usuarios.php?mensaje=Usuario añadido correctamente");
            exit();

        } elseif ($_POST['accion'] === 'editar') {
            if (empty($_POST['id']) || empty($_POST['nombre']) || empty($_POST['email'])) {
                throw new Exception("Datos incompletos");
            }

            // Verificar si el email ya existe para otro usuario
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Usuarios WHERE email = ? AND id_usuario != ?");
            $stmt->execute([
                htmlspecialchars($_POST['email']),
                htmlspecialchars($_POST['id'])
            ]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("El email ya está registrado por otro usuario");
            }

            // Actualizar usuario
            if (!empty($_POST['password'])) {
                // Si se proporciona nueva contraseña
                $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE Usuarios SET nombre = ?, email = ?, password = ?, rol = ? WHERE id_usuario = ?");
                $stmt->execute([
                    htmlspecialchars($_POST['nombre']),
                    htmlspecialchars($_POST['email']),
                    $password_hash,
                    htmlspecialchars($_POST['rol']),
                    htmlspecialchars($_POST['id'])
                ]);
            } else {
                // Si no se cambia la contraseña
                $stmt = $conn->prepare("UPDATE Usuarios SET nombre = ?, email = ?, rol = ? WHERE id_usuario = ?");
                $stmt->execute([
                    htmlspecialchars($_POST['nombre']),
                    htmlspecialchars($_POST['email']),
                    htmlspecialchars($_POST['rol']),
                    htmlspecialchars($_POST['id'])
                ]);
            }

            $conn->commit();
            header("Location: usuarios.php?mensaje=Usuario actualizado correctamente");
            exit();
        }
    }
    
    // Eliminar usuario
    elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
        $conn->beginTransaction();

        if (!isset($_GET['id'])) {
            throw new Exception("ID de usuario no proporcionado");
        }

        // Verificar si el usuario existe
        $stmt = $conn->prepare("SELECT COUNT(*) FROM Usuarios WHERE id_usuario = ?");
        $stmt->execute([htmlspecialchars($_GET['id'])]);
        if ($stmt->fetchColumn() == 0) {
            throw new Exception("Usuario no encontrado");
        }

        // Eliminar usuario
        $stmt = $conn->prepare("DELETE FROM Usuarios WHERE id_usuario = ?");
        $stmt->execute([htmlspecialchars($_GET['id'])]);

        $conn->commit();
        header("Location: usuarios.php?mensaje=Usuario eliminado correctamente");
        exit();
    }

} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollBack();
    }
    header("Location: usuarios.php?error=" . urlencode($e->getMessage()));
    exit();
}
?> 