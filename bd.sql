CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    es_admin ENUM ('admin', 'cliente') DEFAULT 'cliente',
    estado ENUM('activo', 'inactivo', 'pendiente') DEFAULT 'pendiente'
);

CREATE TABLE Películas (
    id_pelicula INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    director VARCHAR(255) NOT NULL,
    fecha_estreno DATE NOT NULL,
    descripcion TEXT,
    categoria ENUM('accion', 'aventura', 'comedia', 'drama', 'terror', 'suspenso', 'ciencia_ficcion', 'fantasia', 'musical', 'animacion', 'documental') NOT NULL,
    poster_url VARCHAR(255) DEFAULT 'default.jpg',
    likes INT DEFAULT 0
);

CREATE TABLE Likes (
    id_like INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_pelicula INT,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_pelicula) REFERENCES Películas(id_pelicula)
);