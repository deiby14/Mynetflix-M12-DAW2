function registrarusuario() {
    var form = document.getElementById("registerfrm");
    var formdata = new FormData(form);
    fetch("./consultas_admin/crear_usuario.php", {
      method: "POST",
      body: formdata,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error al cargar los datos");
        }
        //   console.log(response.json());
        return response.json();
      })
      .then((data) => {
        // console.log(data);
        //   if (data === "error") {
        //   }
        usuariospendiente();
        usuariosactivos();
        // console.log(erorr);
      });
  }