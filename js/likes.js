let isProcessing = false;

function actualizarInterfaz(data) {
    if (!data.success) return;

    // Actualizar el contador de likes y el botón
    const counters = document.querySelectorAll(`#likes-${data.pelicula_id}`);
    counters.forEach(counter => {
        counter.textContent = `${data.likes} Likes`;
    });

    const buttons = document.querySelectorAll(`.like-btn[data-pelicula-id="${data.pelicula_id}"]`);
    buttons.forEach(btn => {
        btn.classList.toggle('liked', data.accion === 'like');
    });

    // Actualizar Top 5 con animación
    if (data.top5) {
        const top5Container = document.querySelector('.top5-container');
        if (top5Container) {
            const oldCards = Array.from(top5Container.children);
            const oldPositions = oldCards.map(card => ({
                id: card.querySelector('.like-btn').dataset.peliculaId,
                rect: card.getBoundingClientRect()
            }));

            let htmlTop5 = '';
            data.top5.forEach(pelicula => {
                htmlTop5 += generateMovieCard(pelicula);
            });
            
            top5Container.innerHTML = htmlTop5;

            // Aplicar animaciones a las nuevas tarjetas
            const newCards = Array.from(top5Container.children);
            newCards.forEach(card => {
                const btn = card.querySelector('.like-btn');
                const id = btn.dataset.peliculaId;
                const oldPosition = oldPositions.find(pos => pos.id === id);
                
                if (oldPosition) {
                    const newRect = card.getBoundingClientRect();
                    const deltaY = oldPosition.rect.top - newRect.top;
                    
                    if (Math.abs(deltaY) > 1) {
                        card.style.transform = `translateY(${deltaY}px)`;
                        requestAnimationFrame(() => {
                            card.style.transition = 'transform 0.5s ease';
                            card.style.transform = '';
                        });
                    }
                }
            });
        }
    }

    // Actualizar todas las películas si hay orden por likes
    if (data.todas) {
        const peliculasContainer = document.getElementById('todasPeliculas');
        if (peliculasContainer) {
            const oldCards = Array.from(peliculasContainer.children);
            const oldPositions = oldCards.map(card => ({
                id: card.querySelector('.like-btn').dataset.peliculaId,
                rect: card.getBoundingClientRect()
            }));

            let htmlTodas = '';
            data.todas.forEach(pelicula => {
                htmlTodas += generateMovieCard(pelicula);
            });
            
            peliculasContainer.innerHTML = htmlTodas;

            // Aplicar animaciones a las nuevas tarjetas
            const newCards = Array.from(peliculasContainer.children);
            newCards.forEach(card => {
                const btn = card.querySelector('.like-btn');
                const id = btn.dataset.peliculaId;
                const oldPosition = oldPositions.find(pos => pos.id === id);
                
                if (oldPosition) {
                    const newRect = card.getBoundingClientRect();
                    const deltaY = oldPosition.rect.top - newRect.top;
                    
                    if (Math.abs(deltaY) > 1) {
                        card.style.transform = `translateY(${deltaY}px)`;
                        requestAnimationFrame(() => {
                            card.style.transition = 'transform 0.5s ease';
                            card.style.transform = '';
                        });
                    }
                }
            });
        }
    }

    reinicializarLikes();
}

function generateMovieCard(pelicula) {
    return `
        <div class="col-6 col-md-4 col-lg-2">
            <div class="movie-card text-center">
                <a href="detalle_pelicula.php?id=${pelicula.id_pelicula}">
                    <img src="./img/${pelicula.poster_url}" 
                         alt="${pelicula.titulo}"
                         class="img-fluid">
                </a>
                <h5 class="movie-title">${pelicula.titulo}</h5>
                <p class="movie-genres">${pelicula.generos.replace(',', ', ')}</p>
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

function reinicializarLikes() {
    initializeLikeButtons();
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
    event.preventDefault();
    const button = event.currentTarget;
    const peliculaId = button.dataset.peliculaId;
    const likesOrder = document.getElementById('filterLikes')?.value || '';
    
    fetch('procesar_like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_pelicula=${peliculaId}&likes_order=${likesOrder}`
    })
    .then(response => response.json())
    .then(data => {
        data.pelicula_id = peliculaId;
        actualizarInterfaz(data);
    })
    .catch(error => console.error('Error:', error));
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

// Inicializar los botones cuando se carga el DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado - Inicializando botones...');
    initializeLikeButtons();
});

// Función para actualizar el Top 5
function actualizarTop5(top5Peliculas) {
    const top5Container = document.querySelector('.top5-container');
    if (!top5Container) return;

    let html = '';
    top5Peliculas.forEach(pelicula => {
        html += generateMovieCard(pelicula);
    });

    top5Container.innerHTML = html;
    reinicializarLikes();
} 