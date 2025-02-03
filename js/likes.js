document.addEventListener('DOMContentLoaded', function() {
    // Obtener todos los botones de like
    const likeButtons = document.querySelectorAll('.like-btn');
    
    // Añadir evento click a cada botón
    likeButtons.forEach(button => {
        // Verificar si el usuario está autenticado
        if (!userIsAuthenticated) {
            button.classList.add('disabled');
            button.disabled = true;
            return;
        }
        
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (!this.disabled) {
                const peliculaId = this.getAttribute('data-pelicula-id');
                likePelicula(peliculaId, this);
            }
        });
    });
});

function likePelicula(peliculaId, button) {
    const formData = new FormData();
    formData.append('id_pelicula', peliculaId);

    fetch('procesar_like.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Actualizar el contador de likes
            const likeCount = document.getElementById(`likes-${peliculaId}`);
            if (likeCount) {
                likeCount.textContent = `${data.likes} Likes`;
            }

            // Actualizar el estilo del botón
            if (data.accion === 'like') {
                button.classList.add('liked');
            } else {
                button.classList.remove('liked');
            }
        } else {
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Opcional: Mostrar un mensaje al usuario
        alert('Ha ocurrido un error al procesar tu like');
    });
}

function initializeLikeButtons() {
    const likeButtons = document.querySelectorAll('.like-button');
    
    likeButtons.forEach(button => {
        // Verificar si el usuario está autenticado
        if (!userIsAuthenticated) {  // Asegúrate de tener esta variable definida
            button.classList.add('disabled');
            button.disabled = true;
            return;
        }
        
        // ... resto del código existente para manejar clicks ...
    });
} 