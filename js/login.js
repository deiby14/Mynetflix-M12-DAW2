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
                window.location.href = data.redirect;
            } else {
                // Determinar qué modal mostrar según el tipo de error
                let modalId;
                switch(data.error_type) {
                    case 'pending':
                        modalId = 'pendingModal';
                        break;
                    case 'inactive':
                        modalId = 'inactiveModal';
                        break;
                    default:
                        modalId = 'errorModal';
                        document.querySelector('.error-message').textContent = data.message;
                }
                const modal = new bootstrap.Modal(document.getElementById(modalId));
                modal.show();
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