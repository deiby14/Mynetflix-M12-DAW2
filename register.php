<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/login-register.css">
    <style>
        .modal-backdrop.show {
            opacity: 0.8;
        }

        .modal-content {
            background-color: rgba(20, 20, 20, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .btn-netflix {
            background-color: #e50914;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-netflix:hover {
            background-color: #f40612;
            color: white;
        }

        .modal .fa-check-circle {
            color: #46d369 !important;
        }

        .modal-header .btn-close-white {
            filter: brightness(0) invert(1);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-video"></i> Netflix</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Register Form -->
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Register</h2>
            <form id="registerForm" action="" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingresa tu nombre">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Ingresa tu correo">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Crea una contraseña">
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirma tu contraseña">
                </div>
                <button type="submit" class="btn-netflix">Registrarse</button>
                <p class="text-center mt-3">¿Ya tienes cuenta? <a href="login.php" class="text-link">Inicia sesión aquí</a></p>
            </form>
        </div>
    </div>

    <!-- Modal de éxito -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="successModalLabel">¡Registro Exitoso!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-check-circle text-success mb-4" style="font-size: 64px;"></i>
                    <h4 class="mb-3">¡Tu registro se ha completado correctamente!</h4>
                    <p class="mb-1">Tu cuenta ha sido creada con éxito.</p>
                    <p>Por favor, espera la activación de tu cuenta para poder iniciar sesión.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-netflix" onclick="window.location.href='login.php'" style="min-width: 150px;">Ir al Login</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/register.js"></script>
</body>
</html>
