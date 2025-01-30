document.getElementById('formularioUsuario').onsubmit = function (event) {
    event.preventDefault();

    const id = document.getElementById('usuarioId').value;
    const nombre = document.getElementById('nombre').value;
    const email = document.getElementById('email').value;
    const estado = document.getElementById('estado').value;
    const es_admin = document.getElementById('es_admin').value;
    const contrasena = document.getElementById('contrasena').value;

    // Validación básica
    if (!nombre || !email || !contrasena) {
        alert('Por favor, rellena todos los campos obligatorios.');
        return;
    }

    const formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('email', email);
    formData.append('estado', estado);
    formData.append('es_admin', es_admin);
    formData.append('contrasena', contrasena);

    if (id) {
        formData.append('id', id);
    }

    console.log('Datos enviados:', {
        id,
        nombre,
        email,
        estado,
        es_admin,
        contrasena,
    });

    let url = id ? 'editar_usuario.php' : 'añadir_usuario.php';

    const ajax = new XMLHttpRequest();
    ajax.open('POST', url);
    ajax.onload = function () {
        console.log('Respuesta en texto:', ajax.responseText);

        if (ajax.status === 200) {
            try {
                const response = JSON.parse(ajax.responseText);
                if (response.success) {
                    ListarUsuarios();
                    ocultarFormulario();
                } else {
                    console.error('Error en la respuesta:', response.message);
                }
            } catch (e) {
                console.error('Error al parsear JSON:', e, ajax.responseText);
            }
        } else {
            console.error('Error en la solicitud:', ajax.statusText);
        }
    };

    ajax.onerror = function () {
        console.error('Error en la conexión AJAX');
    };

    ajax.send(formData);
};



function ListarUsuarios() {
    const resultado = document.getElementById('resultado');
    const ajax = new XMLHttpRequest();

    ajax.open('POST', 'listar_usuarios.php');
    ajax.onload = function() {
        if (ajax.status === 200) {
            const json = JSON.parse(ajax.responseText);
            let tabla = '';

            json.forEach(function(usuario) {
                let str = "<tr><td>" + usuario.nombre + "</td>";
                str += "<td>" + usuario.email + "</td>";
                str += "<td>" + usuario.estado + "</td>";
                str += "<td>" + usuario.es_admin + "</td>";
                str += "<td>";
                str += "<button type='button' class='btn btn-success' onclick='mostrarFormulario(\"editar\", " + JSON.stringify(usuario) + ")'>Editar</button>";
                str += "<button type='button' class='btn btn-danger' onclick='Eliminar(" + usuario.id_usuario + ")'>Eliminar</button>";
                str += "</td></tr>";
                tabla += str;
            });

            resultado.innerHTML = tabla;
        } else {
            resultado.innerText = 'Error';
        }
    };

    ajax.send();
}

function mostrarFormulario(accion, usuario = null) {
    const formulario = document.getElementById('formularioUsuario');
    const titulo = document.getElementById('formularioTitulo');
    const idInput = document.getElementById('usuarioId');
    const nombreInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const estadoInput = document.getElementById('estado');
    const esAdminInput = document.getElementById('es_admin');
    const contrasenaInput = document.getElementById('contrasena');

    if (accion === 'añadir') {
        titulo.textContent = 'Añadir Usuario';
        idInput.value = '';
        nombreInput.value = '';
        emailInput.value = '';
        estadoInput.value = 'activo';
        esAdminInput.value = 'cliente';
        contrasenaInput.value = '';
        contrasenaInput.readOnly = false; // Permitir edición
    } else if (accion === 'editar' && usuario) {
        titulo.textContent = 'Editar Usuario';
        idInput.value = usuario.id_usuario;
        nombreInput.value = usuario.nombre;
        emailInput.value = usuario.email;
        estadoInput.value = usuario.estado;
        esAdminInput.value = usuario.es_admin;
        contrasenaInput.value = '********'; // Mostrar como oculto
        contrasenaInput.readOnly = true; // No permitir edición
    }

    formulario.style.display = 'block';
}

function ocultarFormulario() {
    document.getElementById('formularioUsuario').style.display = 'none';
}
// Eliminar producto

function Eliminar(id) {
    console.log('Intentando eliminar usuario con ID:', id); // Verificar el ID
    const formData = new FormData();
    formData.append('id', id);

    const ajax = new XMLHttpRequest();
    ajax.open('POST', 'eliminar_usuario.php');
    ajax.onload = function() {
        console.log('Respuesta del servidor:', ajax.responseText); // Verificar la respuesta
        if (ajax.status === 200) {
            try {
                const response = JSON.parse(ajax.responseText);
                if (response.success) {
                    console.log('Usuario eliminado con éxito');
                    ListarUsuarios(); // Refrescar la lista de usuarios
                } else {
                    console.error('Error en la respuesta:', response.message);
                }
            } catch (e) {
                console.error('Error al parsear JSON:', e);
            }
        } else {
            console.error('Error al eliminar el usuario');
        }
    };
    ajax.send(formData);
}


