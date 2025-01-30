document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('email', document.getElementById('email').value);
        formData.append('password', document.getElementById('password').value);

        fetch('procesar_login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirigir según el tipo de usuario
                window.location.href = data.redirect;
            } else {
                // Mostrar modal de error
                const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                document.querySelector('.error-message').textContent = data.message;
                errorModal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            document.querySelector('.error-message').textContent = 'Error al conectar con el servidor';
            errorModal.show();
        });
    });
}); 