<?php
require_once 'conexion.php';
header('Content-Type: application/json');

try {
    // Verificar que se recibieron todos los datos
    if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        throw new Exception('Todos los campos son requeridos');
    }

    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $es_admin = 'cliente';
    $estado = 'pendiente';

    // Verificar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        throw new Exception('Las contraseñas no coinciden');
    }

    // Hash de la contraseña solo después de validar que coinciden
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Verificar si el email ya existe
    $stmt = $conn->prepare("SELECT id_usuario FROM Usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        throw new Exception('Este correo electrónico ya está registrado');
    }

    // Insertar nuevo usuario
    $stmt = $conn->prepare("INSERT INTO Usuarios (nombre, email, contrasena, es_admin, estado) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $password, $es_admin, $estado]);

    echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente. Espere la activación de su cuenta.']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 