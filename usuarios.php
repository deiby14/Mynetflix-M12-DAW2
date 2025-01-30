<?php
include 'conexion.php';

try {
    $stmt = $pdo->query("SELECT * FROM Usuarios ORDER BY estado DESC");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener los usuarios: " . $e->getMessage();
    exit();
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4">
            <a href="administrador.php" class="btn btn-secondary">Volver</a>
            <a href="peliculas.php" class="btn btn-info">Ir a Películas</a>
        </div>
        <h1 class="mb-4">Gestión de Usuarios</h1>
        <button class="btn btn-primary mb-3">Añadir Usuario</button>
        <div id="formularioContainer">
            <?php include 'formulario_usuario.php'; ?>
        </div>
        <table class="table table-dark table-hover" id="tablaUsuarios"l>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="resultado">
                <?php foreach ($usuarios as $usuario): ?>
                <tr data-id="<?= $usuario['id_usuario']; ?>">
                    <td><?= htmlspecialchars($usuario['nombre']); ?></td>
                    <td><?= htmlspecialchars($usuario['email']); ?></td>
                    <td><?= htmlspecialchars($usuario['estado']); ?></td>
                    <td><?= htmlspecialchars($usuario['es_admin']); ?></td>
                    <td>
                        <button type="button" class="btn btn-success" onclick='mostrarFormulario("editar", <?= json_encode($usuario); ?>)'>Editar</button>
                        <button type="button" class="btn btn-danger" onclick="Eliminar(<?= $usuario['id_usuario']; ?>)">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="usuarios.js"></script>
</body>
</html>
