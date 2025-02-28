<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Usuarios</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

  <!-- Modal de Registro -->
  <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark text-light">
        <div class="modal-header">
          <h5 class="modal-title" id="registerModalLabel">Registrar Usuario</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="registerfrm" method="post">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input name="nombre" type="text" class="form-control" id="nombre" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input name="email" type="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
              <label for="contrasena" class="form-label">Contraseña</label>
              <input name="contrasena" type="password" class="form-control" id="contrasena" required>
            </div>
            <div class="mb-3">
              <label for="es_admin" class="form-label">Rol</label>
              <select name="es_admin" id="es_admin" class="form-select">
                <option value="admin">Admin</option>
                <option value="cliente">Cliente</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="estado" class="form-label">Estado</label>
              <select name="estado" id="estado" class="form-select">
                <option value="pendiente">Pendiente</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button onclick="registrarusuario()" class="btn btn-primary">Crear Usuario</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Edición -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark text-light">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="editfrm" method="post">
            <!-- Campo oculto para el id del usuario -->
            <input type="hidden" id="id_usuario" name="id_usuario">
            <div class="mb-3">
              <label for="edit_nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
            </div>
            <div class="mb-3">
              <label for="edit_email" class="form-label">Email</label>
              <input type="email" class="form-control" id="edit_email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="edit_contrasena" class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
              <input type="password" class="form-control" id="edit_contrasena" name="contrasena">
            </div>
            <div class="mb-3">
              <label for="edit_es_admin" class="form-label">Rol</label>
              <select id="edit_es_admin" name="es_admin" class="form-select">
                <option value="admin">Admin</option>
                <option value="cliente">Cliente</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_estado" class="form-label">Estado</label>
              <select id="edit_estado" name="estado" class="form-select">
                <option value="pendiente">Pendiente</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button onclick="actualizarUsuario()" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </div>

  <div class="container mt-5">
    <?php
    // Mostrar mensajes de éxito o error si existen
    if (isset($_SESSION['mensaje'])) {
      $tipo = isset($_SESSION['mensaje_tipo']) ? htmlspecialchars($_SESSION['mensaje_tipo']) : 'info';
      echo '<div class="alert alert-' . $tipo . ' alert-dismissible fade show" role="alert">';
      echo htmlspecialchars($_SESSION['mensaje']);
      echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
      echo '</div>';
      unset($_SESSION['mensaje']);
      unset($_SESSION['mensaje_tipo']);
    }
    ?>

    <div class="d-flex justify-content-between mb-4">
      <a href="administrador.php" class="btn btn-secondary">Volver</a>
      <a href="peliculas.php" class="btn btn-info">Ir a Películas</a>
    </div>
    
    <h1 class="mb-4">Gestión de Usuarios</h1>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#registerModal">Registrar Usuario</button>

    <!-- Sección para Usuarios Pendientes -->
    <h2 class="text-light">Usuarios Pendientes</h2>
    <div class="row mb-3">
      <div class="col">
        <input type="text" id="filter_nombre_pendiente" class="form-control" placeholder="Filtrar por nombre">
      </div>
      <div class="col">
        <input type="text" id="filter_email_pendiente" class="form-control" placeholder="Filtrar por email">
      </div>
      <div class="col">
        <select id="filter_estado" class="form-select">
          <option value="">Todos los estados</option>
          <option value="activo">Activo</option>
          <option value="inactivo">Inactivo</option>
          <option value="pendiente">Pendiente</option>
        </select>
      </div>
      <div class="col">
        <button class="btn btn-primary" onclick="usuariospendiente()">Filtrar</button>
        <button class="btn btn-primary" onclick="limpiar_pendiente()">Borrar</button>
      </div>
    </div>
    <table class="table table-dark table-hover">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Email</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody id="usuarios_pendientes"></tbody>
    </table>

    <!-- Sección para Usuarios Activos -->
    <h2 class="text-light">Usuarios Activos</h2>
    <div class="row mb-3">
      <div class="col">
        <input type="text" id="filter_nombre_activo" class="form-control" placeholder="Filtrar por nombre">
      </div>
      <div class="col">
        <input type="text" id="filter_email_activo" class="form-control" placeholder="Filtrar por email">
      </div>
      <div class="col">
        <select id="filter_rol_activo" class="form-select">
          <option value="">Todos los roles</option>
          <option value="admin">Admin</option>
          <option value="cliente">Cliente</option>
        </select>
      </div>
      <div class="col">
        <button class="btn btn-primary" onclick="usuariosactivos()">Filtrar</button>
        <button class="btn btn-primary" onclick="limpiar_activos()">Borrar</button>
      </div>
    </div>
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
      <tbody id="usuarios_activos"></tbody>
    </table>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Scripts personalizados -->
  <script src="./js/agregar_usuario.js"></script>
  <script src="./js/editar_usuario.js"></script>
  <script src="./js/usuarios_activos.js"></script>
  <script src="./js/usuarios_pendientes.js"></script>
  <script src="./js/filtro_activo.js"></script>
  <script src="./js/filtro_pendiente.js"></script>

  <?php
  // Cerrar la conexión a la base de datos si existe
  if (isset($conn)) {
    $conn = null;
  }
  ?>
</body>
</html>
