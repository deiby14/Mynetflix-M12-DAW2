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

    // Verificar contraseña
    if (!password_verify($password, $usuario['contrasena'])) {
        throw new Exception('Email o contraseña incorrectos');
    }

    // Si es cliente, verificar estado
    if ($usuario['es_admin'] === 'cliente') {
        if ($usuario['estado'] === 'pendiente') {
            echo json_encode([
                'success' => false,
                'error_type' => 'pending',
                'message' => 'Tu cuenta está pendiente de activación'
            ]);
            exit;
        }
        
        if ($usuario['estado'] === 'inactivo') {
            echo json_encode([
                'success' => false,
                'error_type' => 'inactive',
                'message' => 'Tu cuenta ha sido suspendida'
            ]);
            exit;
        }
    }

    // Si llegamos aquí, el usuario puede iniciar sesión
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
        'error_type' => 'error',
        'message' => $e->getMessage()
    ]);
}
?> 