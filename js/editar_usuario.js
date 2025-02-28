// Función para cargar los datos del usuario en el formulario de edición
function cargarUsuarioParaEditar(id) {
    const formData = new FormData();
    formData.append("id_usuario", id);
  
    fetch("./consultas_admin/obtener_usuario.php", {
      method: "POST",
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        // Rellenamos el formulario de edición con los datos recibidos
        document.getElementById("id_usuario").value = data.id_usuario;
        document.getElementById("edit_nombre").value = data.nombre;
        document.getElementById("edit_email").value = data.email;
        // Dejamos el campo de contraseña en blanco
        document.getElementById("edit_contrasena").value = "";
        document.getElementById("edit_es_admin").value = data.es_admin;
        document.getElementById("edit_estado").value = data.estado;
  
        // Mostrar el modal de edición
        let editModal = new bootstrap.Modal(document.getElementById("editModal"));
        editModal.show();
      });
  }
  
  // Función para enviar los datos actualizados al servidor
  function actualizarUsuario() {
    const form = document.getElementById("editfrm");
    const formData = new FormData(form);
  
    fetch("./consultas_admin/edit_usuario.php", {
      method: "POST",
      body: formData,
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === "success") {
          alert("Usuario actualizado correctamente");
          // Ocultar el modal de edición
          let modalEl = document.getElementById("editModal");
          let modalInstance = bootstrap.Modal.getInstance(modalEl);
          modalInstance.hide();
          // Recargar las tablas de usuarios (activos y pendientes)
          usuariosactivos();
          usuariospendiente();
        } else {
          alert("Error: " + data.message);
        }
      });
  }
  