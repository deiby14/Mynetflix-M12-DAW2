<?php
session_start();
require_once 'conexion.php';
header('Content-Type: application/json');

try {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        throw new Exception('Todos los campos son requeridos');
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Buscar usuario por email
    $stmt = $conn->prepare("SELECT id_usuario, nombre, email, contrasena, es_admin, estado FROM Usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        throw new Exception('Email o contraseña incorrectos');
    }

    // Verificar estado de la cuenta
    if ($usuario['estado'] === 'pendiente') {
        throw new Exception('Tu cuenta está pendiente de activación');
    }

    if ($usuario['estado'] === 'inactivo') {
        throw new Exception('Tu cuenta está inactiva');
    }

    // Verificar contraseña
    if (!password_verify($password, $usuario['contrasena'])) {
        throw new Exception('Email o contraseña incorrectos');
    }

    // Iniciar sesión
    $_SESSION['usuario'] = [
        'id' => $usuario['id_usuario'],
        'nombre' => $usuario['nombre'],
        'email' => $usuario['email'],
        'es_admin' => $usuario['es_admin']
    ];

    // Determinar la redirección según el tipo de usuario
    $redirect = $usuario['es_admin'] === 'admin' ? 'administrador.php' : 'index.php';

    echo json_encode([
        'success' => true,
        'redirect' => $redirect,
        'message' => 'Inicio de sesión exitoso'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 