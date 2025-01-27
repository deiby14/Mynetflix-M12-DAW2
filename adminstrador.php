<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrador - Plataforma de Streaming</title>
    <!-- Incluir CSS y JS necesarios -->
</head>
<body>
    <h1>Panel de Administración</h1>

    <!-- Gestión de Usuarios -->
    <section id="gestion-usuarios">
        <h2>Gestión de Usuarios</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se llenará con datos de usuarios desde la base de datos -->
            </tbody>
        </table>
    </section>

    <!-- Gestión de Películas -->
    <section id="gestion-peliculas">
        <h2>Gestión de Películas</h2>
        <button onclick="mostrarFormularioAgregar()">Agregar Película</button>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Director</th>
                    <th>Likes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se llenará con datos de películas desde la base de datos -->
            </tbody>
        </table>
    </section>

    <!-- Formulario para agregar/modificar películas -->
    <div id="formulario-pelicula" style="display:none;">
        <h3>Agregar/Modificar Película</h3>
        <form id="form-pelicula">
            <input type="text" name="titulo" placeholder="Título" required>
            <input type="text" name="director" placeholder="Director" required>
            <input type="date" name="fecha_estreno" required>
            <textarea name="descripcion" placeholder="Descripción"></textarea>
            <select name="categoria" required>
                <option value="accion">Acción</option>
                <option value="aventura">Aventura</option>
                <!-- Más opciones según la base de datos -->
            </select>
            <button type="submit">Guardar</button>
        </form>
    </div>

    <script>
        // Funciones JavaScript para manejar acciones de usuario y películas
        function mostrarFormularioAgregar() {
            document.getElementById('formulario-pelicula').style.display = 'block';
        }
    </script>
</body>
</html>