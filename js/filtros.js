document.addEventListener('DOMContentLoaded', function() {
    console.log('Filtros.js cargado'); // Debug

    const filterInputs = {
        titulo: document.getElementById('filterTitulo'),
        categoria: document.getElementById('filterCategoria'),
        director: document.getElementById('filterDirector'),
        likes: document.getElementById('filterLikes'),
        userLikes: document.getElementById('filterUserLikes')
    };

    // Debug: verificar que todos los elementos existen
    Object.entries(filterInputs).forEach(([key, element]) => {
        if (!element) {
            console.error(`Elemento no encontrado: ${key}`);
        }
    });

    const resetButton = document.getElementById('resetFilters');
    const peliculasContainer = document.getElementById('todasPeliculas');

    if (!peliculasContainer) {
        console.error('Contenedor de películas no encontrado');
        return;
    }

    // Si el usuario no está autenticado, solo deshabilitar los filtros
    if (!userIsAuthenticated) {
        console.log('Usuario no autenticado - filtros desactivados');
        Object.values(filterInputs).forEach(input => {
            if (input) input.disabled = true;
        });
        if (resetButton) resetButton.disabled = true;
        return;
    }

    let timeoutId;

    function reinicializarLikes() {
        if (typeof initializeLikeButtons === 'function') {
            initializeLikeButtons();
        }
    }

    // Actualizar los valores del select de likes
    const userLikesSelect = document.querySelector('select[name="user_likes"]');
    if (userLikesSelect) {
        userLikesSelect.innerHTML = `
            <option value="">Todos</option>
            <option value="con_likes">Mis Likes</option>
            <option value="sin_likes">Sin Likes</option>
        `;
    }

    function aplicarFiltros() {
        const filtros = {
            titulo: filterInputs.titulo ? filterInputs.titulo.value : '',
            categoria: filterInputs.categoria ? filterInputs.categoria.value : '',
            director: filterInputs.director ? filterInputs.director.value : '',
            likes_order: filterInputs.likes ? filterInputs.likes.value : '',
            user_likes: filterInputs.userLikes ? filterInputs.userLikes.value : ''
        };

        console.log('Valores de filtros:', filtros);

        const params = new URLSearchParams({
            ...filtros,
            page: '1'
        });

        fetch(`procesar_filtros.php?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos:', data);
                if (data.success) {
                    if (peliculasContainer) {
                        peliculasContainer.innerHTML = data.html;
                        reinicializarLikes();
                    }
                    
                    // Actualizar la interfaz si hay actualizaciones disponibles
                    if (typeof updateMovieInterface === 'function' && data.actualizacion) {
                        updateMovieInterface(data.actualizacion);
                    }
                } else {
                    console.error('Error en la respuesta:', data.error);
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
            });
    }

    // Event listeners
    Object.values(filterInputs).forEach(input => {
        if (!input) return;
        
        if (input.type === 'text') {
            input.addEventListener('input', () => {
                console.log('Input text changed'); // Debug
                clearTimeout(timeoutId);
                timeoutId = setTimeout(aplicarFiltros, 300);
            });
        } else {
            input.addEventListener('change', () => {
                console.log('Select changed'); // Debug
                aplicarFiltros();
            });
        }
    });

    if (resetButton) {
        resetButton.addEventListener('click', () => {
            console.log('Reset clicked'); // Debug
            Object.values(filterInputs).forEach(input => {
                if (input) input.value = '';
            });
            aplicarFiltros();
        });
    }

    // Cargar películas inicialmente
    console.log('Cargando películas iniciales...'); // Debug
    aplicarFiltros();
}); 