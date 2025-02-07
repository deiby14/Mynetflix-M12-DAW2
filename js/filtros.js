document.addEventListener('DOMContentLoaded', function() {
    // Solo inicializar los filtros si el usuario está autenticado
    if (!userIsAuthenticated) {
        console.log('Usuario no autenticado - filtros desactivados');
        return;
    }

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

    let timeoutId;

    function reinicializarLikes() {
        // Usar la función de inicialización global
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

    function aplicarFiltros(page = 1) {
        const titulo = document.querySelector('input[name="titulo"]').value;
        const categoria = document.querySelector('select[name="categoria"]').value;
        const director = document.querySelector('select[name="director"]').value;
        const likesOrder = document.querySelector('select[name="likes_order"]').value;
        const userLikes = document.querySelector('select[name="user_likes"]').value;

        console.log('Valores de filtros:', {
            titulo,
            categoria,
            director,
            likesOrder,
            userLikes
        });

        const params = new URLSearchParams({
            titulo: titulo,
            categoria: categoria,
            director: director,
            likes_order: likesOrder,
            user_likes: userLikes,
            page: page
        });

        console.log('Parámetros:', params.toString()); // Debug

        fetch(`procesar_filtros.php?${params}`)
            .then(response => {
                console.log('Respuesta recibida'); // Debug
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data); // Debug
                if (data.success) {
                    peliculasContainer.innerHTML = data.html;
                    reinicializarLikes(); // Reinicializar los likes después de actualizar el contenido
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