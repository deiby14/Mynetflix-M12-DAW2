document.addEventListener('DOMContentLoaded', function() {
    // Variables para la paginación
    const peliculasPorPagina = 5;
    let paginaActual = 1;
    let peliculasFiltradas = [];

    // Elementos del DOM
    const busquedaTitulo = document.getElementById('busqueda_titulo');
    const busquedaDirector = document.getElementById('busqueda_director');
    const filtroCategoria = document.getElementById('filtro_categoria');
    const filtroFecha = document.getElementById('filtro_fecha');
    const ordenLikes = document.getElementById('orden_likes');
    const tablaPeliculas = document.querySelector('#tabla_peliculas tbody');

    // Event listeners para los filtros
    busquedaTitulo.addEventListener('input', aplicarFiltros);
    busquedaDirector.addEventListener('input', aplicarFiltros);
    filtroCategoria.addEventListener('change', aplicarFiltros);
    filtroFecha.addEventListener('change', aplicarFiltros);
    ordenLikes.addEventListener('change', aplicarFiltros);

    // Función para obtener las películas filtradas mediante AJAX
    function aplicarFiltros() {
        const formData = new FormData();
        formData.append('titulo', busquedaTitulo.value);
        formData.append('director', busquedaDirector.value);
        formData.append('categoria', filtroCategoria.value);
        formData.append('fecha', filtroFecha.value);
        formData.append('orden', ordenLikes.value);

        fetch('filtrar_peliculas.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            peliculasFiltradas = data;
            paginaActual = 1;
            mostrarPeliculas();
            actualizarPaginacion();
        })
        .catch(error => console.error('Error:', error));
    }

    // Función para mostrar las películas en la tabla
    function mostrarPeliculas() {
        const inicio = (paginaActual - 1) * peliculasPorPagina;
        const fin = inicio + peliculasPorPagina;
        const peliculasPagina = peliculasFiltradas.slice(inicio, fin);

        tablaPeliculas.innerHTML = '';

        peliculasPagina.forEach(pelicula => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${pelicula.titulo}</td>
                <td>${pelicula.director}</td>
                <td>${pelicula.fecha_estreno}</td>
                <td>${pelicula.generos}</td>
                <td>${pelicula.likes}</td>
                <td>
                    <a href="?accion=editar&id=${pelicula.id_pelicula}" 
                       class="btn btn-warning btn-sm">Editar</a>
                    <a href="procesar_pelicula.php?accion=eliminar&id=${pelicula.id_pelicula}" 
                       class="btn btn-danger btn-sm">
                        Eliminar
                    </a>
                </td>
            `;
            tablaPeliculas.appendChild(fila);
        });
    }

    // Función para actualizar la paginación
    function actualizarPaginacion() {
        const totalPaginas = Math.ceil(peliculasFiltradas.length / peliculasPorPagina);
        const paginacionContainer = document.getElementById('paginacion');
        
        if (!paginacionContainer) {
            const container = document.createElement('div');
            container.id = 'paginacion';
            container.className = 'mt-3 d-flex justify-content-center';
            document.querySelector('#tabla_peliculas').after(container);
        }

        const html = `
            <nav aria-label="Navegación de páginas">
                <ul class="pagination">
                    <li class="page-item ${paginaActual === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-pagina="${paginaActual - 1}">Anterior</a>
                    </li>
                    ${generarBotonesPagina(totalPaginas)}
                    <li class="page-item ${paginaActual === totalPaginas ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-pagina="${paginaActual + 1}">Siguiente</a>
                    </li>
                </ul>
            </nav>
        `;

        document.getElementById('paginacion').innerHTML = html;
        
        // Event listeners para los botones de paginación
        document.querySelectorAll('#paginacion .page-link').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const nuevaPagina = parseInt(e.target.dataset.pagina);
                if (nuevaPagina > 0 && nuevaPagina <= totalPaginas) {
                    paginaActual = nuevaPagina;
                    mostrarPeliculas();
                    actualizarPaginacion();
                }
            });
        });
    }

    // Función auxiliar para generar los botones de página
    function generarBotonesPagina(totalPaginas) {
        let botones = '';
        for (let i = 1; i <= totalPaginas; i++) {
            botones += `
                <li class="page-item ${i === paginaActual ? 'active' : ''}">
                    <a class="page-link" href="#" data-pagina="${i}">${i}</a>
                </li>
            `;
        }
        return botones;
    }

    // Inicializar con todas las películas
    aplicarFiltros();
}); 