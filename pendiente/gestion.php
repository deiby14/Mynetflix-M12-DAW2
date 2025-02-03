<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Estado</th>
        <th>Acción</th>
    </tr>
    <tbody id="listaUsuarios"></tbody>
</table>

<script>
function cargarUsuariosPendientes() {
    fetch('obtenerUsuariosPendientes.php')
        .then(response => response.json())
        .then(usuarios => {
            let tabla = document.getElementById('listaUsuarios');
            tabla.innerHTML = ''; // Limpiar tabla

            usuarios.forEach(usuario => {
                let fila = `<tr>
                    <td>${usuario.id_usuario}</td>
                    <td>${usuario.nombre}</td>
                    <td>${usuario.email}</td>
                    <td>${usuario.estado}</td>
                    <td>
                        <button onclick="cambiarEstado(${usuario.id_usuario}, 'aprobar')">Aprobar</button>
                        <button onclick="cambiarEstado(${usuario.id_usuario}, 'rechazar')">Rechazar</button>
                    </td>
                </tr>`;
                tabla.innerHTML += fila;
            });
        });
}

function cambiarEstado(id, accion) {
    fetch('aprobarUsuario.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_usuario=${id}&accion=${accion}`
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        cargarUsuariosPendientes(); // Refrescar la lista
    });
}

// Cargar usuarios pendientes al cargar la página
cargarUsuariosPendientes();
</script>
