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
    fetch('procesar_like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_pelicula=${peliculaId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar todos los botones y contadores para esta película
            document.querySelectorAll(`.like-btn[data-pelicula-id="${peliculaId}"]`).forEach(btn => {
                if (data.accion === 'like') {
                    btn.classList.add('liked');
                } else {
                    btn.classList.remove('liked');
                }
            });

            // Actualizar todos los contadores de likes para esta película
            document.querySelectorAll(`#likes-${peliculaId}`).forEach(counter => {
                counter.textContent = `${data.likes} Likes`;
            });
        } else {
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Función para inicializar los botones de like
function initializeLikeButtons() {
    console.log('Inicializando botones de like...'); // Debug
    const likeButtons = document.querySelectorAll('.like-btn');
    
    likeButtons.forEach(button => {
        // Si el usuario no está autenticado, deshabilitar el botón
        if (!userIsAuthenticated) {
            button.classList.add('disabled');
            button.disabled = true;
            return;
        }
        
        // Eliminar eventos anteriores
        button.removeEventListener('click', null);
        
        // Añadir nuevo evento click
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (!this.disabled) {
                const peliculaId = this.getAttribute('data-pelicula-id');
                likePelicula(peliculaId, this);
            }
        });
    });
}

// Inicializar los botones cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Loaded - Inicializando likes...'); // Debug
    initializeLikeButtons();
});

// Hacer la función disponible globalmente
window.initializeLikeButtons = initializeLikeButtons; 