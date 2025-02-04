let isProcessing = false;

function actualizarInterfaz(data, peliculaId) {
    if (isProcessing) return;
    isProcessing = true;

    try {
        console.log('Actualizando interfaz con datos:', data); // Debug

        // 1. Actualizar el botón específico y su contador
        const buttons = document.querySelectorAll(`.like-btn[data-pelicula-id="${peliculaId}"]`);
        const counters = document.querySelectorAll(`#likes-${peliculaId}`);
        
        buttons.forEach(btn => {
            btn.classList.toggle('liked', data.accion === 'like');
        });
        
        counters.forEach(counter => {
            counter.textContent = `${data.likes} Likes`;
        });

        // 2. Actualizar ambas secciones siempre que haya datos de actualización
        if (data.actualizacion) {
            // Actualizar Top 5
            const top5Container = document.querySelector('#peliculasTop5');
            if (top5Container && data.actualizacion.top5) {
                console.log('Actualizando Top 5...'); // Debug
                const top5HTML = data.actualizacion.top5
                    .map(pelicula => generarHTMLPelicula(pelicula))
                    .join('');
                top5Container.innerHTML = top5HTML;
            }

            // Actualizar todas las películas
            const todasContainer = document.querySelector('#todasPeliculas');
            if (todasContainer && data.actualizacion.todas) {
                console.log('Actualizando Todas las Películas...'); // Debug
                const todasHTML = data.actualizacion.todas
                    .map(pelicula => generarHTMLPelicula(pelicula))
                    .join('');
                todasContainer.innerHTML = todasHTML;
            }

            // Reinicializar los botones de like
            setTimeout(() => {
                initializeLikeButtons();
                console.log('Botones de like reinicializados'); // Debug
            }, 0);
        }
    } catch (error) {
        console.error('Error en actualizarInterfaz:', error);
    } finally {
        isProcessing = false;
    }
}

function generarHTMLPelicula(pelicula) {
    return `
        <div class="col-2 mb-4">
            <div class="movie-card text-center">
                <a href="detalle_pelicula.php?id=${pelicula.id_pelicula}">
                    <img src="./img/${pelicula.poster_url}" 
                         alt="${pelicula.titulo}"
                         class="img-fluid">
                </a>
                <h5 class="movie-title">${pelicula.titulo}</h5>
                <button class="like-btn ${pelicula.user_liked ? 'liked' : ''}"
                        data-pelicula-id="${pelicula.id_pelicula}">
                    <i class="fas fa-thumbs-up"></i> Like
                </button>
                <div class="like-count" id="likes-${pelicula.id_pelicula}">
                    ${pelicula.likes} Likes
                </div>
            </div>
        </div>
    `;
}

function likePelicula(peliculaId, button) {
    if (isProcessing) return;
    
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
            actualizarInterfaz(data, peliculaId);
        } else {
            console.error('Error en la respuesta:', data.message);
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);
    })
    .finally(() => {
        isProcessing = false;
    });
}

function initializeLikeButtons() {
    document.querySelectorAll('.like-btn:not([data-initialized])').forEach(button => {
        if (!userIsAuthenticated) {
            button.classList.add('disabled');
            button.disabled = true;
            return;
        }
        
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (!this.disabled && !isProcessing) {
                const peliculaId = this.getAttribute('data-pelicula-id');
                likePelicula(peliculaId, this);
            }
        });

        button.setAttribute('data-initialized', 'true');
    });
}

// Inicializar los botones cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM cargado - Inicializando botones...'); // Debug
    initializeLikeButtons();
}); 