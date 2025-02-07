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

        // 2. Obtener los filtros actuales
        const filtrosActuales = {
            titulo: document.querySelector('input[name="titulo"]').value,
            categoria: document.querySelector('select[name="categoria"]').value,
            director: document.querySelector('select[name="director"]').value,
            likes_order: document.querySelector('select[name="likes_order"]').value,
            user_likes: document.querySelector('select[name="user_likes"]').value
        };

        // 3. Hacer una nueva petición con los filtros actuales
        const params = new URLSearchParams(filtrosActuales);
        
        fetch(`procesar_filtros.php?${params}`)
            .then(response => response.json())
            .then(filteredData => {
                if (filteredData.success) {
                    // Actualizar el contenedor de todas las películas
                    const todasContainer = document.querySelector('#todasPeliculas');
                    if (todasContainer) {
                        todasContainer.innerHTML = filteredData.html;
                    }

                    // Actualizar el Top 5 si existe
                    const top5Container = document.querySelector('#peliculasTop5');
                    if (top5Container && filteredData.actualizacion && filteredData.actualizacion.top5) {
                        let top5HTML = '';
                        filteredData.actualizacion.top5.forEach(pelicula => {
                            top5HTML += generarHTMLPelicula(pelicula);
                        });
                        top5Container.innerHTML = top5HTML;
                    }

                    // Reinicializar los botones de like
                    initializeLikeButtons();
                }
            })
            .catch(error => {
                console.error('Error al actualizar con filtros:', error);
            });

    } catch (error) {
        console.error('Error en actualizarInterfaz:', error);
    } finally {
        isProcessing = false;
    }
}

function generarHTMLPelicula(pelicula) {
    return `
        <div class="col-6 col-md-4 col-lg-2">
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