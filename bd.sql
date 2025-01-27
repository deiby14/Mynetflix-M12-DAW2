-- Eliminar la base de datos si ya existe
DROP DATABASE IF EXISTS db_netflix;

-- Crear la base de datos
CREATE DATABASE db_netflix 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE=utf8mb4_unicode_ci;
USE db_netflix;

CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(190) NOT NULL,
    email VARCHAR(190) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    es_admin ENUM ('admin', 'cliente') DEFAULT 'cliente',
    estado ENUM('activo', 'inactivo', 'pendiente') DEFAULT 'pendiente'
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Peliculas (
    id_pelicula INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    director VARCHAR(255) NOT NULL,
    fecha_estreno DATE NOT NULL,
    descripcion TEXT,
    categoria ENUM('accion', 'aventura', 'comedia', 'drama', 'terror', 'suspenso', 'ciencia_ficcion', 'fantasia', 'musical', 'animacion', 'documental') NOT NULL,
    poster_url VARCHAR(255) DEFAULT 'default.jpg',
    likes INT DEFAULT 0
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Likes (
    id_like INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_pelicula INT,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_pelicula) REFERENCES Peliculas(id_pelicula)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;