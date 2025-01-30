document.addEventListener('DOMContentLoaded', function() {
    console.log('Script de registro cargado'); // Para verificar que el script se carga

    const form = document.getElementById('registerForm');
    if (!form) {
        console.error('No se encontró el formulario de registro');
        return;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Formulario enviado'); // Para verificar que el evento se dispara

        const formData = new FormData();
        formData.append('nombre', document.getElementById('nombre').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('password', document.getElementById('password').value);
        formData.append('confirm_password', document.getElementById('confirm_password').value);

        // Mostrar los datos que se están enviando (solo para desarrollo)
        console.log('Datos a enviar:', {
            nombre: document.getElementById('nombre').value,
            email: document.getElementById('email').value,
            password: 'hidden',
            confirm_password: 'hidden'
        });

        fetch('procesar_registro.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos:', data); // Para ver la respuesta del servidor
            if (data.success) {
                // Mostrar el modal de éxito
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
                
                // Limpiar el formulario
                form.reset();
                
                // El redireccionamiento ahora se maneja con el botón del modal
            } else {
                alert(data.message || 'Error en el registro');
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            alert('Ocurrió un error durante el registro. Por favor, intenta nuevamente.');
        });
    });
}); 