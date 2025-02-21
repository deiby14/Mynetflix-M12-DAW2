document.addEventListener('DOMContentLoaded', function() {
<<<<<<< HEAD
    console.log('Filtros.js cargado'); // Debug

    const filterInputs = {
        titulo: document.getElementById('filterTitulo'),
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

    // Añadir manejo de géneros
    const generoCheckboxes = document.querySelectorAll('.filter-genero');
    const dropdownButton = document.getElementById('dropdownGeneros');

    function updateGeneroButtonText() {
        const selectedGeneros = getSelectedGeneros();
        if (selectedGeneros.length === 0) {
            dropdownButton.textContent = 'Seleccionar Géneros';
        } else if (selectedGeneros.length === 1) {
            dropdownButton.textContent = selectedGeneros[0];
        } else {
            dropdownButton.textContent = `${selectedGeneros.length} géneros seleccionados`;
        }
    }

    function getSelectedGeneros() {
        const selectedGeneros = [];
        generoCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedGeneros.push(checkbox.value);
            }
        });
        return selectedGeneros;
    }

    function aplicarFiltros() {
        const filtros = {
            titulo: filterInputs.titulo ? filterInputs.titulo.value : '',
            director: filterInputs.director ? filterInputs.director.value : '',
            likes_order: filterInputs.likes ? filterInputs.likes.value : '',
            user_likes: filterInputs.userLikes ? filterInputs.userLikes.value : '',
            generos: getSelectedGeneros().join(',')
        };

        console.log('Aplicando filtros:', filtros); // Debug

        const params = new URLSearchParams(filtros);
        
        fetch(`procesar_filtros.php?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data); // Debug
                if (data.success) {
                    if (peliculasContainer) {
                        peliculasContainer.innerHTML = data.html;
                        reinicializarLikes();
                    }
                } else {
                    console.error('Error en la respuesta:', data.error);
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
            });
    }

    // Prevenir que los clicks en los checkboxes cierren el dropdown
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });

    // Event listeners para los checkboxes de géneros
    generoCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            updateGeneroButtonText();
            aplicarFiltros();
        });
    });

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
            // Limpiar checkboxes de géneros
            generoCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateGeneroButtonText();
            aplicarFiltros();
        });
    }

    // Cargar películas inicialmente
    console.log('Cargando películas iniciales...'); // Debug
=======
    // Referencias a los elementos de filtro
    const busquedaTitulo = document.getElementById('busqueda_titulo');
    const busquedaDirector = document.getElementById('busqueda_director');
    const filtroCategoria = document.getElementById('filtro_categoria');
    const filtroFecha = document.getElementById('filtro_fecha');
    const ordenLikes = document.getElementById('orden_likes');

    // Objeto para almacenar los filtros activos
    let filtrosActivos = {
        titulo: '',
        director: '',
        categoria: '',
        fecha: '',
        orden: 'DESC' // Valor por defecto
    };

    // Función para aplicar los filtros
    function aplicarFiltros() {
        // Para debug
        console.log('Aplicando filtros:', filtrosActivos);
        
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'filtrar_peliculas.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('tabla_peliculas').innerHTML = xhr.responseText;
                // Para debug
                console.log('Respuesta recibida');
            }
        };

        // Construir la cadena de datos
        const datos = new URLSearchParams(filtrosActivos).toString();
        // Para debug
        console.log('Enviando datos:', datos);
        xhr.send(datos);
    }

    // Event listeners para los filtros
    busquedaTitulo.addEventListener('input', function() {
        filtrosActivos.titulo = this.value;
        aplicarFiltros();
    });

    busquedaDirector.addEventListener('input', function() {
        filtrosActivos.director = this.value;
        aplicarFiltros();
    });

    filtroCategoria.addEventListener('change', function() {
        filtrosActivos.categoria = this.value;
        aplicarFiltros();
    });

    filtroFecha.addEventListener('change', function() {
        filtrosActivos.fecha = this.value;
        aplicarFiltros();
    });

    ordenLikes.addEventListener('change', function() {
        // Para debug
        console.log('Cambio en orden de likes:', this.value);
        filtrosActivos.orden = this.value;
        aplicarFiltros();
    });

    // Cargar datos iniciales
>>>>>>> 805d5c417ba756f81691797f2a91f2645421753b
    aplicarFiltros();
}); 