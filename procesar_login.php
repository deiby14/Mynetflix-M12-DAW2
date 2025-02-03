<?php
session_start();
require_once 'conexion.php';
header('Content-Type: application/json');

try {
    // Validar que los campos no estén vacíos
    if (empty($_POST['email']) || empty($_POST['password'])) {
        throw new Exception('Por favor, completa todos los campos');
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El formato del correo electrónico no es válido');
    }

    // Buscar usuario por email
    $stmt = $conn->prepare("SELECT id_usuario, nombre, email, contrasena, es_admin, estado FROM Usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si existe el usuario y la contraseña
    if (!$usuario || !password_verify($password, $usuario['contrasena'])) {
        throw new Exception('El correo o la contraseña son incorrectos');
    }

    // Verificar estado de la cuenta
    if ($usuario['es_admin'] === 'cliente') {
        switch ($usuario['estado']) {
            case 'pendiente':
                echo json_encode([
                    'success' => false,
                    'error_type' => 'pending',
                    'message' => 'Tu cuenta está pendiente de activación. Por favor, espera la confirmación del administrador.'
                ]);
                exit;
            
            case 'inactivo':
                echo json_encode([
                    'success' => false,
                    'error_type' => 'inactive',
                    'message' => 'Tu cuenta ha sido suspendida. Por favor, contacta al administrador para más información.'
                ]);
                exit;
        }
    }

    // Si todo está bien, crear la sesión
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
        'message' => '¡Bienvenido ' . $usuario['nombre'] . '!'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error_type' => 'error',
        'message' => $e->getMessage()
    ]);
}
?> 