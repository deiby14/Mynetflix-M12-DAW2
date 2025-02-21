document.addEventListener('DOMContentLoaded', function() {
    console.log('Script de registro cargado'); // Para verificar que el script se carga

    const form = document.getElementById('registerForm');
    const inputs = {
        nombre: document.getElementById('nombre'),
        email: document.getElementById('email'),
        password: document.getElementById('password'),
        confirm_password: document.getElementById('confirm_password')
    };

    // Funciones de validación
    const validaciones = {
        nombre: (valor) => {
            if (!valor) return 'El nombre es requerido';
            if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(valor)) return 'El nombre solo debe contener letras';
            return '';
        },
        email: (valor) => {
            if (!valor) return 'El correo es requerido';
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor)) return 'Ingresa un correo válido';
            return '';
        },
        password: (valor) => {
            if (!valor) return 'La contraseña es requerida';
            if (!/[A-Z]/.test(valor)) return 'Debe contener al menos una mayúscula';
            if (!/[a-z]/.test(valor)) return 'Debe contener al menos una minúscula';
            if (!/[0-9]/.test(valor)) return 'Debe contener al menos un número';
            if (valor.length < 6) return 'La contraseña debe tener al menos 6 caracteres';
            return '';
        },
        confirm_password: (valor) => {
            if (!valor) return 'Confirma tu contraseña';
            if (valor !== inputs.password.value) return 'Las contraseñas no coinciden';
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

        fetch('procesar_registro.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
                form.reset();
                // Limpiar las clases de validación
                Object.values(inputs).forEach(input => {
                    input.classList.remove('is-valid', 'is-invalid');
                });
            } else {
                if (data.error_type === 'email_exists') {
                    const emailExistsModal = new bootstrap.Modal(document.getElementById('emailExistsModal'));
                    emailExistsModal.show();
                } else {
                    alert(data.message || 'Error en el registro');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error en el registro. Por favor, intenta nuevamente.');
        });
    });
}); 