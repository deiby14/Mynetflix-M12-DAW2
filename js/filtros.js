document.addEventListener('DOMContentLoaded', function() {
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
    aplicarFiltros();
}); 