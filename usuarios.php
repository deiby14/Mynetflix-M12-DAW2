<?php
include 'conexion.php';

// Configurar el modo de error de PDO
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Desactivar autocommit
    $conn->beginTransaction();
    
    // Sanitizar y validar el ID si existe
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new Exception("ID de usuario inválido");
        }
    }

    // Consulta principal de usuarios
    $stmt = $conn->query("SELECT * FROM Usuarios ORDER BY estado DESC");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Si estamos editando, obtener datos del usuario de forma segura
    if (isset($_GET['accion']) && $_GET['accion'] == 'editar' && isset($id)) {
        $stmt = $conn->prepare("SELECT * FROM Usuarios WHERE id_usuario = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario) {
            throw new Exception("Usuario no encontrado");
        }
    }

    // Confirmar la transacción
    $conn->commit();

} catch (Exception $e) {
    // Revertir la transacción en caso de error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    // Mostrar un mensaje de error amigable al usuario
    $error_message = "Ha ocurrido un error: " . htmlspecialchars($e->getMessage());
    // Registrar el error real para debugging
    error_log("Error en usuarios.php: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #141414;
            color: #fff;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4">
            <a href="administrador.php" class="btn btn-secondary">Volver</a>
            <a href="peliculas.php" class="btn btn-info">Ir a Películas</a>
        </div>
        <h1 class="mb-4">Gestión de Usuarios</h1>
        <a href="?accion=mostrar_form" class="btn btn-primary mb-3">Añadir Usuario</a>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>
        
        <?php 
        if(isset($_GET['accion']) && $_GET['accion'] == 'mostrar_form'): 
        ?>
            <form action="procesar_usuario.php" method="POST" class="mb-4">
                <h3>Añadir Usuario</h3>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select class="form-control" id="estado" name="estado">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="es_admin">Rol</label>
                    <select class="form-control" id="es_admin" name="es_admin">
                        <option value="cliente">Cliente</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                
                <button type="submit" name="accion" value="añadir" class="btn btn-primary">Guardar</button>
                <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php 
        elseif(isset($_GET['accion']) && $_GET['accion'] == 'editar' && isset($id) && isset($usuario)): 
        ?>
            <form action="procesar_usuario.php" method="POST" class="mb-4">
                <h3>Editar Usuario</h3>
                <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?= htmlspecialchars($usuario['nombre']) ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= htmlspecialchars($usuario['email']) ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select class="form-control" id="estado" name="estado">
                        <option value="activo" <?= $usuario['estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="inactivo" <?= $usuario['estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="es_admin">Rol</label>
                    <select class="form-control" id="es_admin" name="es_admin">
                        <option value="cliente" <?= $usuario['es_admin'] == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                        <option value="admin" <?= $usuario['es_admin'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                </div>
                
                <button type="submit" name="accion" value="editar" class="btn btn-primary">Guardar</button>
                <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>

        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= htmlspecialchars($usuario['estado']) ?></td>
                        <td><?= htmlspecialchars($usuario['es_admin']) ?></td>
                        <td>
                            <a href="?accion=editar&id=<?= $usuario['id_usuario'] ?>" 
                               class="btn btn-warning btn-sm">Editar</a>
                            <a href="procesar_usuario.php?accion=eliminar&id=<?= $usuario['id_usuario'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
