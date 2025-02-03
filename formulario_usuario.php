<form id="formularioUsuario" style="display: none;">
    <h3 id="formularioTitulo">Añadir Usuario</h3>
    <input type="hidden" id="usuarioId" name="id">
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="estado">Estado:</label>
        <select class="form-control" id="estado" name="estado">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
            <option value="pendiente">Pendiente</option>
        </select>
    </div>
    <div class="form-group">
        <label for="es_admin">Rol:</label>
        <select class="form-control" id="es_admin" name="es_admin">
            <option value="cliente">Cliente</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <div class="form-group">
        <label for="contrasena">contrasena:</label>
        <input type="password" class="form-control" id="contrasena" name="contrasena" required>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
</form> 