setInterval(usuariosactivos, 5000); // 5000 milisegundos = 5 segundos

usuariosactivos();

function usuariosactivos() {
  fetch("./consultas_admin/usuarios_activos.php", { method: "POST" })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al cargar los datos");
      }
      // console.log(response);
      // return response.text();
      return response.json();
    })
    .then((data) => {
      // console.log(data);
      let tbody = document.getElementById("usuarios_activos");
      let tabla = "";
      data.forEach((informacion) => {
        tabla += "<tr>";
        tabla += "<td>" + informacion.nombre + "</td>";
        tabla += "<td>" + informacion.email + "</td>";
        tabla += "<td>" + informacion.estado + "</td>";
        tabla += "<td>" + informacion.es_admin + "</td>";
        tabla += '<td><button type="button" class="btn btn-warning" onclick="cargarUsuarioParaEditar(' +
        informacion.id_usuario +
        ')">Editar</button> ';
tabla += '<button type="button" class="btn btn-danger" onclick="eliminar(' +
        informacion.id_usuario +
        ')">Eliminar</button></td>';

        tabla += "</tr>";
        tbody.innerHTML = tabla;
      });
    });
}

function eliminar(id) {
  if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
    // Creamos un objeto FormData para enviar el ID del usuario
    const formData = new FormData();
    formData.append("id_usuario", id);

    fetch("./consultas_admin/eliminar_usuario.php", {
      method: "POST",
      body: formData
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error al eliminar el usuario");
        }
        return response.json();
      })
      .then((data) => {
        // console.log(data);
        // Si el servidor responde 'success', refrescamos la lista de usuarios
        if (data == "success") {
          usuariosactivos();
          alert("Usuario eliminado correctamente");
        } else {
          alert("Error al eliminar el usuario: " + data.message);
        }
      })
  }
}
function editarUsuario() {
  var form = document.getElementById("editfrm"); // Selecciona el formulario de edición
  var formData = new FormData(form); // Crea un FormData con los datos del formulario

  fetch("./consultas_admin/edit_usuario.php", {
      method: "POST",
      body: formData,
  })
  .then((response) => {
      if (!response.ok) {
          throw new Error("Error al procesar la solicitud");
      }
      return response.json(); // Convertir la respuesta en JSON
  })
  .then((data) => {
      if (data.status === "success") {
          alert("Usuario actualizado correctamente"); // Mensaje de éxito
          $("#editModal").modal("hide"); // Cierra el modal
          usuariospendiente(); // Recarga la lista de usuarios pendientes
          usuariosactivos(); // Recarga la lista de usuarios activos
      } else {
          alert("Error: " + data.message); // Muestra un mensaje de error
      }
  })
  .catch((error) => {
      console.error("Error:", error);
  });
}

