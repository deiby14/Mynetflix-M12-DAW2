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
    contrasena VARCHAR(255) NOT NULL,
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

-- Insertando 30 películas en la tabla Peliculas
INSERT INTO Peliculas (titulo, director, fecha_estreno, descripcion, categoria, poster_url, likes) VALUES
('Inception', 'Christopher Nolan', '2010-07-16', 'Un ladrón que entra en los sueños de las personas.', 'ciencia_ficcion', 'Inception.jpg', 400),
('The Dark Knight', 'Christopher Nolan', '2008-07-18', 'Batman enfrenta a su peor enemigo, el Joker.', 'accion', 'TheDarkKnight.jpg', 400),
('Interstellar', 'Christopher Nolan', '2014-11-07', 'Exploradores viajan a través de un agujero de gusano en el espacio.', 'ciencia_ficcion', 'Interstellar.jpg', 400),
('Avatar', 'James Cameron', '2009-12-18', 'Un ex-marine en la luna de Pandora.', 'ciencia_ficcion', 'Avatar.jpg', 400),
('Titanic', 'James Cameron', '1997-12-19', 'Un romance en medio del desastre marítimo más famoso.', 'drama', 'Titanic.jpg', 450),
('The Godfather', 'Francis Ford Coppola', '1972-03-24', 'La historia de la familia criminal Corleone.', 'drama', 'TheGodfather.jpg', 0),
('Pulp Fiction', 'Quentin Tarantino', '1994-10-14', 'Historias interconectadas de criminales de Los Ángeles.', 'suspenso', 'PulpFiction.jpg', 0),
('The Matrix', 'Lana Wachowski', '1999-03-31', 'Un hacker descubre la realidad detrás de su mundo.', 'ciencia_ficcion', 'TheMatrix.jpg', 0),
('Gladiator', 'Ridley Scott', '2000-05-05', 'Un general romano traicionado busca venganza.', 'accion', 'Gladiator.jpg', 0),
('The Shawshank Redemption', 'Frank Darabont', '1994-09-23', 'Un hombre inocente en prisión encuentra la redención.', 'drama', 'TheShawshankRedemption.jpg', 0),
('Fight Club', 'David Fincher', '1999-10-15', 'Un club secreto de peleas cambia vidas.', 'suspenso', 'FightClub.jpg', 0),
('Forrest Gump', 'Robert Zemeckis', '1994-07-06', 'La vida de un hombre con un corazón puro.', 'drama', 'ForrestGump.jpg', 0),
('The Avengers', 'Joss Whedon', '2012-05-04', 'Superhéroes se unen para salvar el mundo.', 'accion', 'TheAvengers.jpg', 0),
('The Lion King', 'Roger Allers', '1994-06-24', 'Un joven león enfrenta su destino como rey.', 'animacion', 'TheLionKing.jpg', 0),
('Frozen', 'Chris Buck', '2013-11-27', 'Una princesa con poderes de hielo descubre su destino.', 'animacion', 'Frozen.jpg', 0),
('Toy Story', 'John Lasseter', '1995-11-22', 'Juguetes que cobran vida cuando los humanos no están cerca.', 'animacion', 'ToyStory.jpg', 0),
('Coco', 'Lee Unkrich', '2017-11-22', 'Un niño viaja al mundo de los muertos para encontrar su herencia musical.', 'animacion', 'Coco.jpeg', 0),
('Jurassic Park', 'Steven Spielberg', '1993-06-11', 'Dinosaurios clonados causan caos en un parque temático.', 'aventura', 'JurassicPark.jpg', 0),
('The Grand Budapest Hotel', 'Wes Anderson', '2014-03-28', 'Las aventuras de un conserje en un hotel lujoso.', 'comedia', 'TheGrandBudapestHotel.jpg',0 ),
('Parasite', 'Bong Joon-ho', '2019-05-30', 'Una familia pobre se infiltra en una familia rica.', 'suspenso', 'Parasite.jpg', 0),
('Mad Max: Fury Road', 'George Miller', '2015-05-15', 'Un mundo post-apocalíptico lleno de caos.', 'accion', 'MadMaxFuryRoad.jpg', 0),
('The Revenant', 'Alejandro G. Iñárritu', '2015-12-25', 'Un explorador lucha por sobrevivir.', 'aventura', 'TheRevenant.jpg', 0),
('Zootopia', 'Byron Howard', '2016-03-04', 'Una conejita policía resuelve un misterio en una ciudad animal.', 'animacion', 'Zootopia.jpg', 0),
('Gravity', 'Alfonso Cuarón', '2013-10-04', 'Dos astronautas luchan por sobrevivir en el espacio.', 'ciencia_ficcion', 'Gravity.jpg', 0),
('The Conjuring', 'James Wan', '2013-07-19', 'Investigadores paranormales enfrentan una presencia oscura.', 'terror', 'TheConjuring.jpg', 0),
('It', 'Andy Muschietti', '2017-09-08', 'Un grupo de niños enfrenta a un payaso aterrador.', 'terror', 'It.jpg', 0),
('Get Out', 'Jordan Peele', '2017-02-24', 'Un hombre afroamericano descubre un oscuro secreto familiar.', 'terror', 'GetOut.jpg', 0),
('La La Land', 'Damien Chazelle', '2016-12-09', 'Un pianista y una actriz luchan por sus sueños en Los Ángeles.', 'musical', 'LaLaLand.jpg', 0),
('The Greatest Showman', 'Michael Gracey', '2017-12-20', 'La historia de P.T. Barnum y su espectáculo circense.', 'musical', 'Showman.jpg', 0),
('A Star Is Born', 'Bradley Cooper', '2018-10-05', 'Una joven cantante y un músico luchan por el éxito.', 'musical', 'Born.jpg', 0);
