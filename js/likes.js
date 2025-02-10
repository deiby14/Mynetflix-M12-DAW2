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

function initializeLikeButtons() {
    document.querySelectorAll('.like-btn').forEach(button => {
        button.removeEventListener('click', handleLikeClick);
        button.addEventListener('click', handleLikeClick);
    });
}

function getCurrentFilters() {
    return {
        titulo: document.getElementById('filterTitulo')?.value || '',
        categoria: document.getElementById('filterCategoria')?.value || '',
        director: document.getElementById('filterDirector')?.value || '',
        likes_order: document.getElementById('filterLikes')?.value || '',
        user_likes: document.getElementById('filterUserLikes')?.value || ''
    };
}

function handleLikeClick(event) {
    const button = event.currentTarget;
    const peliculaId = button.dataset.peliculaId;
    const currentFilters = getCurrentFilters();
    
    fetch('procesar_like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_pelicula=${peliculaId}&${new URLSearchParams(currentFilters).toString()}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar el botón
            button.classList.toggle('liked');
            
            // Actualizar el contador de likes
            const likeCount = document.getElementById(`likes-${peliculaId}`);
            if (likeCount) {
                likeCount.textContent = `${data.likes} Likes`;
            }
            
            // Actualizar solo el Top 5
            if (data.actualizacion?.top5) {
                updateTop5Interface(data.actualizacion.top5);
            }

            // Para la sección de todas las películas, aplicar los filtros actuales
            if (data.actualizacion?.todas) {
                updateTodasInterface(data.actualizacion.todas, currentFilters);
            }

            // Reinicializar los botones de like después de actualizar la interfaz
            initializeLikeButtons();
        } else {
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateTop5Interface(top5Data) {
    const top5Container = document.querySelector('.container:has(h2 i.fa-star)').querySelector('.row');
    if (top5Container) {
        let top5HTML = '';
        top5Data.forEach(pelicula => {
            top5HTML += generateMovieCard(pelicula);
        });
        top5Container.innerHTML = top5HTML;
        // Reinicializar los botones de like después de actualizar el Top 5
        initializeLikeButtons();
    }
}

function updateTodasInterface(todasData, filters) {
    const todasContainer = document.getElementById('todasPeliculas');
    if (todasContainer) {
        // Filtrar las películas según los filtros actuales
        let peliculasFiltradas = todasData;
        
        if (filters.titulo) {
            peliculasFiltradas = peliculasFiltradas.filter(p => 
                p.titulo.toLowerCase().includes(filters.titulo.toLowerCase())
            );
        }
        if (filters.categoria) {
            peliculasFiltradas = peliculasFiltradas.filter(p => 
                p.generos.includes(filters.categoria)
            );
        }
        if (filters.director) {
            peliculasFiltradas = peliculasFiltradas.filter(p => 
                p.director === filters.director
            );
        }
        if (filters.user_likes === 'con_likes') {
            peliculasFiltradas = peliculasFiltradas.filter(p => p.user_liked);
        } else if (filters.user_likes === 'sin_likes') {
            peliculasFiltradas = peliculasFiltradas.filter(p => !p.user_liked);
        }
        if (filters.likes_order) {
            peliculasFiltradas.sort((a, b) => {
                return filters.likes_order === 'desc' ? 
                    b.likes - a.likes : 
                    a.likes - b.likes;
            });
        }

        let todasHTML = '';
        peliculasFiltradas.forEach(pelicula => {
            todasHTML += generateMovieCard(pelicula);
        });
        todasContainer.innerHTML = todasHTML;
        // Reinicializar los botones de like después de actualizar Todas las películas
        initializeLikeButtons();
    }
}

function generateMovieCard(pelicula) {
    return `
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <div class="movie-card text-center">
                <a href="detalle_pelicula.php?id=${pelicula.id_pelicula}">
                    <img src="./img/${pelicula.poster_url}" 
                         alt="${pelicula.titulo}"
                         class="img-fluid">
                </a>
                <h5 class="movie-title">${pelicula.titulo}</h5>
                <p class="movie-genres">${pelicula.generos.replace(/,/g, ', ')}</p>
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

// Inicializar los botones cuando se carga el DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado - Inicializando botones...');
    initializeLikeButtons();
}); 