document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const inputs = {
        email: document.getElementById('email'),
        password: document.getElementById('password')
    };

    // Funciones de validación
    const validaciones = {
        email: (valor) => {
            if (!valor.trim()) return 'El correo es requerido';
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor)) return 'Ingresa un correo válido';
            return '';
        },
        password: (valor) => {
            if (!valor.trim()) return 'La contraseña es requerida';
            return '';
        }
    };

    // Agregar validación en tiempo real para cada campo
    Object.keys(inputs).forEach(campo => {
        inputs[campo].addEventListener('input', function() {
            validarCampo(this, campo);
        });

        inputs[campo].addEventListener('blur', function() {
            validarCampo(this, campo);
        });
    });

    function validarCampo(input, campo) {
        const error = validaciones[campo](input.value);
        const feedbackElement = input.nextElementSibling;
        
        if (error) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            feedbackElement.textContent = error;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            feedbackElement.textContent = '';
        }
        return !error;
    }

    function validarFormulario() {
        let isValid = true;
        Object.keys(inputs).forEach(campo => {
            if (!validarCampo(inputs[campo], campo)) {
                isValid = false;
            }
        });
        return isValid;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validarFormulario()) {
            return;
        }

        const formData = new FormData(form);

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
