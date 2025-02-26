setInterval(usuariospendiente, 5000); // 5000 milisegundos = 5 segundos

usuariospendiente();
function usuariospendiente() {
  const formData = new FormData();
    formData.append("filter_nombre", document.getElementById("filter_nombre_pendiente").value);
    formData.append("filter_email", document.getElementById("filter_email_pendiente").value);
    formData.append("filter_estado", document.getElementById("filter_estado").value);

    fetch("./consultas_admin/usuarios_pendiente_filtro.php", {
      method: "POST",
      body: formData
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al cargar los datos");
      }
      return response.json();
    })
    .then((data) => {
      // console.log(data);
      let tbody = document.getElementById("usuarios_pendientes");
      tbody.innerHTML = "";
      let tabla = "";
      if (data == "") {
        tabla += "<tr>";
        tabla += "<td colspan='4' class='text-center'>  No hay usuarios pendientes </td>";
        tabla += "</tr>";
      } else {
        data.forEach((informacion) => {
          tabla += "<tr>";
          tabla += "<td>" + informacion.nombre + "</td>";
          tabla += "<td>" + informacion.email + "</td>";
          tabla += "<td>" + informacion.estado + "</td>";
          tabla +=
            '<td><button type="button" class="btn btn-success" onclick="aprobar(' +
            informacion.id_usuario +
            ')">Aprobar</button> ';
          tabla +=
            '<button type="button" class="btn btn-danger" onclick="eliminar(' +
            informacion.id_usuario +
            ')">Rechazar</button></td> ';
          tabla += "</tr>";
        });
      }
      tbody.innerHTML = tabla;
    });
}

function aprobar(id) {
  // Creamos un objeto FormData para enviar el ID del usuario
  const formData = new FormData();
  formData.append("id_usuario", id);

  fetch("./consultas_admin/aprobar_usuario.php", {
    method: "POST",
    body: formData,
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
        usuariospendiente();
        usuariosactivos();
        alert("Usuario Aprobado correctamente");
      } else {
        alert("Error al aprobar el usuario: " + data.message);
      }
    })
}


function limpiar_pendiente() {


  document.getElementById("filter_nombre_pendiente").value = ""; //
  document.getElementById("filter_email_pendiente").value = ""; //
  document.getElementById("filter_estado").value = ""; //
  usuariospendiente(); // Recarga la lista de usuarios pendientes
  

  
}