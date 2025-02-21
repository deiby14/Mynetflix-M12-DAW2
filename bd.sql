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
<<<<<<< HEAD
=======
    categoria ENUM('accion', 'aventura', 'comedia', 'drama', 'terror', 'suspenso', 'cienciaficcion', 'fantasia', 'musical', 'animacion', 'documental') NOT NULL,
>>>>>>> 805d5c417ba756f81691797f2a91f2645421753b
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

-- Crear tabla de géneros
CREATE TABLE Generos (
    id_genero INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla de relación películas-géneros
CREATE TABLE Peliculas_Generos (
    id_pelicula INT,
    id_genero INT,
    PRIMARY KEY (id_pelicula, id_genero),
    FOREIGN KEY (id_pelicula) REFERENCES Peliculas(id_pelicula),
    FOREIGN KEY (id_genero) REFERENCES Generos(id_genero)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar géneros
INSERT INTO Generos (nombre) VALUES 
('accion'),
('aventura'),
('comedia'),
('drama'),
('terror'),
('suspenso'),
('ciencia_ficcion'),
('fantasia'),
('musical'),
('animacion'),
('documental');

-- Insertando 30 películas en la tabla Peliculas
<<<<<<< HEAD
INSERT INTO Peliculas (titulo, director, fecha_estreno, descripcion, poster_url, likes) VALUES
('Inception', 'Christopher Nolan', '2010-07-16', 'Un ladrón que entra en los sueños de las personas.', 'Inception.jpg', 400),
('The Dark Knight', 'Christopher Nolan', '2008-07-18', 'Batman enfrenta a su peor enemigo, el Joker.', 'TheDarkKnight.jpg', 400),
('Interstellar', 'Christopher Nolan', '2014-11-07', 'Exploradores viajan a través de un agujero de gusano en el espacio.', 'Interstellar.jpg', 400),
('Avatar', 'James Cameron', '2009-12-18', 'Un ex-marine en la luna de Pandora.', 'Avatar.jpg', 400),
('Titanic', 'James Cameron', '1997-12-19', 'Un romance en medio del desastre marítimo más famoso.', 'Titanic.jpg', 450),
('The Godfather', 'Francis Ford Coppola', '1972-03-24', 'La historia de la familia criminal Corleone.', 'TheGodfather.jpg', 0),
('Pulp Fiction', 'Quentin Tarantino', '1994-10-14', 'Historias interconectadas de criminales de Los Ángeles.', 'PulpFiction.jpg', 0),
('The Matrix', 'Lana Wachowski', '1999-03-31', 'Un hacker descubre la realidad detrás de su mundo.', 'TheMatrix.jpg', 0),
('Gladiator', 'Ridley Scott', '2000-05-05', 'Un general romano traicionado busca venganza.', 'Gladiator.jpg', 0),
('The Shawshank Redemption', 'Frank Darabont', '1994-09-23', 'Un hombre inocente en prisión encuentra la redención.', 'TheShawshankRedemption.jpg', 0),
('Fight Club', 'David Fincher', '1999-10-15', 'Un club secreto de peleas cambia vidas.', 'FightClub.jpg', 0),
('Forrest Gump', 'Robert Zemeckis', '1994-07-06', 'La vida de un hombre con un corazón puro.', 'ForrestGump.jpg', 0),
('The Avengers', 'Joss Whedon', '2012-05-04', 'Superhéroes se unen para salvar el mundo.', 'TheAvengers.jpg', 0),
('The Lion King', 'Roger Allers', '1994-06-24', 'Un joven león enfrenta su destino como rey.', 'TheLionKing.jpg', 0),
('Frozen', 'Chris Buck', '2013-11-27', 'Una princesa con poderes de hielo descubre su destino.', 'Frozen.jpg', 0),
('Toy Story', 'John Lasseter', '1995-11-22', 'Juguetes que cobran vida cuando los humanos no están cerca.', 'ToyStory.jpg', 0),
('Coco', 'Lee Unkrich', '2017-11-22', 'Un niño viaja al mundo de los muertos para encontrar su herencia musical.', 'Coco.jpeg', 0),
('Jurassic Park', 'Steven Spielberg', '1993-06-11', 'Dinosaurios clonados causan caos en un parque temático.', 'JurassicPark.jpg', 0),
('The Grand Budapest Hotel', 'Wes Anderson', '2014-03-28', 'Las aventuras de un conserje en un hotel lujoso.', 'TheGrandBudapestHotel.jpg',0 ),
('Parasite', 'Bong Joon-ho', '2019-05-30', 'Una familia pobre se infiltra en una familia rica.', 'Parasite.jpg', 0),
('Mad Max: Fury Road', 'George Miller', '2015-05-15', 'Un mundo post-apocalíptico lleno de caos.', 'MadMaxFuryRoad.jpg', 0),
('The Revenant', 'Alejandro G. Iñárritu', '2015-12-25', 'Un explorador lucha por sobrevivir.', 'TheRevenant.jpg', 0),
('Zootopia', 'Byron Howard', '2016-03-04', 'Una conejita policía resuelve un misterio en una ciudad animal.', 'Zootopia.jpg', 0),
('Gravity', 'Alfonso Cuarón', '2013-10-04', 'Dos astronautas luchan por sobrevivir en el espacio.', 'Gravity.jpg', 0),
('The Conjuring', 'James Wan', '2013-07-19', 'Investigadores paranormales enfrentan una presencia oscura.', 'TheConjuring.jpg', 0),
('It', 'Andy Muschietti', '2017-09-08', 'Un grupo de niños enfrenta a un payaso aterrador.', 'It.jpg', 0),
('Get Out', 'Jordan Peele', '2017-02-24', 'Un hombre afroamericano descubre un oscuro secreto familiar.', 'GetOut.jpg', 0),
('La La Land', 'Damien Chazelle', '2016-12-09', 'Un pianista y una actriz luchan por sus sueños en Los Ángeles.', 'LaLaLand.jpg', 0),
('The Greatest Showman', 'Michael Gracey', '2017-12-20', 'La historia de P.T. Barnum y su espectáculo circense.', 'Showman.jpg', 0),
('A Star Is Born', 'Bradley Cooper', '2018-10-05', 'Una joven cantante y un músico luchan por el éxito.', 'Born.jpg', 0);

-- Insertar relaciones película-género (ejemplo)
INSERT INTO Peliculas_Generos (id_pelicula, id_genero) VALUES
-- Inception
(1, 7), -- ciencia_ficcion
(1, 1), -- accion
(1, 6), -- suspenso
(1, 2), -- aventura

-- The Dark Knight
(2, 1), -- accion
(2, 6), -- suspenso
(2, 4), -- drama
(2, 2), -- aventura

-- Interstellar
(3, 7), -- ciencia_ficcion
(3, 4), -- drama
(3, 2), -- aventura
(3, 8), -- fantasia

-- Avatar
(4, 7), -- ciencia_ficcion
(4, 2), -- aventura
(4, 1), -- accion
(4, 8), -- fantasia

-- Titanic
(5, 4), -- drama
(5, 9), -- musical
(5, 2), -- aventura

-- The Godfather
(6, 4), -- drama
(6, 6), -- suspenso
(6, 1), -- accion

-- Pulp Fiction
(7, 6), -- suspenso
(7, 4), -- drama
(7, 1), -- accion
(7, 3), -- comedia

-- The Matrix
(8, 7), -- ciencia_ficcion
(8, 1), -- accion
(8, 8), -- fantasia
(8, 6), -- suspenso

-- Gladiator
(9, 1), -- accion
(9, 4), -- drama
(9, 2), -- aventura

-- The Shawshank Redemption
(10, 4), -- drama
(10, 6), -- suspenso

-- Fight Club
(11, 4), -- drama
(11, 6), -- suspenso
(11, 3), -- comedia

-- Forrest Gump
(12, 4), -- drama
(12, 3), -- comedia
(12, 2), -- aventura

-- The Avengers
(13, 1), -- accion
(13, 2), -- aventura
(13, 7), -- ciencia_ficcion
(13, 8), -- fantasia

-- The Lion King
(14, 10), -- animacion
(14, 4), -- drama
(14, 2), -- aventura
(14, 9), -- musical

-- Frozen
(15, 10), -- animacion
(15, 2), -- aventura
(15, 9), -- musical
(15, 8), -- fantasia

-- Toy Story
(16, 10), -- animacion
(16, 3), -- comedia
(16, 2), -- aventura
(16, 8), -- fantasia

-- Coco
(17, 10), -- animacion
(17, 8), -- fantasia
(17, 9), -- musical
(17, 4), -- drama

-- Jurassic Park
(18, 2), -- aventura
(18, 7), -- ciencia_ficcion
(18, 6), -- suspenso
(18, 1), -- accion

-- The Grand Budapest Hotel
(19, 3), -- comedia
(19, 2), -- aventura
(19, 4), -- drama

-- Parasite
(20, 6), -- suspenso
(20, 4), -- drama
(20, 3), -- comedia

-- Mad Max: Fury Road
(21, 1), -- accion
(21, 2), -- aventura
(21, 7), -- ciencia_ficcion

-- The Revenant
(22, 2), -- aventura
(22, 4), -- drama
(22, 6), -- suspenso

-- Zootopia
(23, 10), -- animacion
(23, 3), -- comedia
(23, 2), -- aventura
(23, 6), -- suspenso

-- Gravity
(24, 7), -- ciencia_ficcion
(24, 6), -- suspenso
(24, 4), -- drama

-- The Conjuring
(25, 5), -- terror
(25, 6), -- suspenso
(25, 4), -- drama

-- It
(26, 5), -- terror
(26, 4), -- drama
(26, 6), -- suspenso

-- Get Out
(27, 5), -- terror
(27, 6), -- suspenso
(27, 4), -- drama
(27, 3), -- comedia

-- La La Land
(28, 9), -- musical
(28, 4), -- drama
(28, 3), -- comedia

-- The Greatest Showman
(29, 9), -- musical
(29, 4), -- drama
(29, 2), -- aventura

-- A Star Is Born
(30, 9), -- musical
(30, 4), -- drama
(30, 3); -- comedia
=======
INSERT INTO Peliculas (titulo, director, fecha_estreno, descripcion, categoria, poster_url, likes) VALUES
<<<<<<<<< Temporary merge branch 1
('Inception', 'Christopher Nolan', '2010-07-16', 'Un ladrón que entra en los sueños de las personas.', 'ciencia_ficcion', 'avatar.jpg', 400),
('The Dark Knight', 'Christopher Nolan', '2008-07-18', 'Batman enfrenta a su peor enemigo, el Joker.', 'accion', 'capitan.jpg', 400),
('Interstellar', 'Christopher Nolan', '2014-11-07', 'Exploradores viajan a través de un agujero de gusano en el espacio.', 'ciencia_ficcion', 'joker.jpg', 400),
('Avatar', 'James Cameron', '2009-12-18', 'Un ex-marine en la luna de Pandora.', 'ciencia_ficcion', 'mufasa.jpg', 400),
('Titanic', 'James Cameron', '1997-12-19', 'Un romance en medio del desastre marítimo más famoso.', 'drama', 'uncharted.jpg', 450),
('The Godfather', 'Francis Ford Coppola', '1972-03-24', 'La historia de la familia criminal Corleone.', 'drama', 'https://via.placeholder.com/300x450?text=The+Godfather', 220),
('Pulp Fiction', 'Quentin Tarantino', '1994-10-14', 'Historias interconectadas de criminales de Los Ángeles.', 'suspenso', 'https://via.placeholder.com/300x450?text=Pulp+Fiction', 190),
('The Matrix', 'Lana Wachowski', '1999-03-31', 'Un hacker descubre la realidad detrás de su mundo.', 'ciencia_ficcion', 'https://via.placeholder.com/300x450?text=The+Matrix', 270),
('Gladiator', 'Ridley Scott', '2000-05-05', 'Un general romano traicionado busca venganza.', 'accion', 'https://via.placeholder.com/300x450?text=Gladiator', 160),
('The Shawshank Redemption', 'Frank Darabont', '1994-09-23', 'Un hombre inocente en prisión encuentra la redención.', 'drama', 'https://via.placeholder.com/300x450?text=Shawshank', 280),
('Fight Club', 'David Fincher', '1999-10-15', 'Un club secreto de peleas cambia vidas.', 'suspenso', 'https://via.placeholder.com/300x450?text=Fight+Club', 230),
('Forrest Gump', 'Robert Zemeckis', '1994-07-06', 'La vida de un hombre con un corazón puro.', 'drama', 'https://via.placeholder.com/300x450?text=Forrest+Gump', 210),
('The Avengers', 'Joss Whedon', '2012-05-04', 'Superhéroes se unen para salvar el mundo.', 'accion', 'https://via.placeholder.com/300x450?text=The+Avengers', 320),
('The Lion King', 'Roger Allers', '1994-06-24', 'Un joven león enfrenta su destino como rey.', 'animacion', 'https://via.placeholder.com/300x450?text=Lion+King', 270),
('Frozen', 'Chris Buck', '2013-11-27', 'Una princesa con poderes de hielo descubre su destino.', 'animacion', 'https://via.placeholder.com/300x450?text=Frozen', 340),
('Toy Story', 'John Lasseter', '1995-11-22', 'Juguetes que cobran vida cuando los humanos no están cerca.', 'animacion', 'https://via.placeholder.com/300x450?text=Toy+Story', 310),
('Coco', 'Lee Unkrich', '2017-11-22', 'Un niño viaja al mundo de los muertos para encontrar su herencia musical.', 'animacion', 'https://via.placeholder.com/300x450?text=Coco', 290),
('Jurassic Park', 'Steven Spielberg', '1993-06-11', 'Dinosaurios clonados causan caos en un parque temático.', 'aventura', 'https://via.placeholder.com/300x450?text=Jurassic+Park', 260),
('The Grand Budapest Hotel', 'Wes Anderson', '2014-03-28', 'Las aventuras de un conserje en un hotel lujoso.', 'comedia', 'https://via.placeholder.com/300x450?text=Grand+Budapest', 140),
('Parasite', 'Bong Joon-ho', '2019-05-30', 'Una familia pobre se infiltra en una familia rica.', 'suspenso', 'https://via.placeholder.com/300x450?text=Parasite', 310),
('Mad Max: Fury Road', 'George Miller', '2015-05-15', 'Un mundo post-apocalíptico lleno de caos.', 'accion', 'https://via.placeholder.com/300x450?text=Mad+Max', 280),
('The Revenant', 'Alejandro G. Iñárritu', '2015-12-25', 'Un explorador lucha por sobrevivir.', 'aventura', 'https://via.placeholder.com/300x450?text=The+Revenant', 150),
('Zootopia', 'Byron Howard', '2016-03-04', 'Una conejita policía resuelve un misterio en una ciudad animal.', 'animacion', 'https://via.placeholder.com/300x450?text=Zootopia', 230),
('Gravity', 'Alfonso Cuarón', '2013-10-04', 'Dos astronautas luchan por sobrevivir en el espacio.', 'ciencia_ficcion', 'https://via.placeholder.com/300x450?text=Gravity', 200),
('The Conjuring', 'James Wan', '2013-07-19', 'Investigadores paranormales enfrentan una presencia oscura.', 'terror', 'https://via.placeholder.com/300x450?text=Conjuring', 220),
('It', 'Andy Muschietti', '2017-09-08', 'Un grupo de niños enfrenta a un payaso aterrador.', 'terror', 'https://via.placeholder.com/300x450?text=It', 190),
('Get Out', 'Jordan Peele', '2017-02-24', 'Un hombre afroamericano descubre un oscuro secreto familiar.', 'terror', 'https://via.placeholder.com/300x450?text=Get+Out', 210),
('La La Land', 'Damien Chazelle', '2016-12-09', 'Un pianista y una actriz luchan por sus sueños en Los Ángeles.', 'musical', 'https://via.placeholder.com/300x450?text=La+La+Land', 180),
('The Greatest Showman', 'Michael Gracey', '2017-12-20', 'La historia de P.T. Barnum y su espectáculo circense.', 'musical', 'https://via.placeholder.com/300x450?text=Showman', 170),
('A Star Is Born', 'Bradley Cooper', '2018-10-05', 'Una joven cantante y un músico luchan por el éxito.', 'musical', 'https://via.placeholder.com/300x450?text=Star+Is+Born', 160);
=========
('Inception', 'Christopher Nolan', '2010-07-16', 'Un ladrón que entra en los sueños de las personas.', 'cienciaficcion', 'Inception.jpg', 400),
('The Dark Knight', 'Christopher Nolan', '2008-07-18', 'Batman enfrenta a su peor enemigo, el Joker.', 'accion', 'TheDarkKnight.jpg', 400),
('Interstellar', 'Christopher Nolan', '2014-11-07', 'Exploradores viajan a través de un agujero de gusano en el espacio.', 'accion', 'Interstellar.jpg', 400),
('Avatar', 'James Cameron', '2009-12-18', 'Un ex-marine en la luna de Pandora.', 'accion', 'Avatar.jpg', 400),
('Titanic', 'James Cameron', '1997-12-19', 'Un romance en medio del desastre marítimo más famoso.', 'drama', 'Titanic.jpg', 450),
('The Godfather', 'Francis Ford Coppola', '1972-03-24', 'La historia de la familia criminal Corleone.', 'drama', 'TheGodfather.jpg', 0),
('Pulp Fiction', 'Quentin Tarantino', '1994-10-14', 'Historias interconectadas de criminales de Los Ángeles.', 'suspenso', 'PulpFiction.jpg', 0),
('The Matrix', 'Lana Wachowski', '1999-03-31', 'Un hacker descubre la realidad detrás de su mundo.', 'accion', 'TheMatrix.jpg', 0),
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
('Gravity', 'Alfonso Cuarón', '2013-10-04', 'Dos astronautas luchan por sobrevivir en el espacio.', 'accion', 'Gravity.jpg', 0),
('The Conjuring', 'James Wan', '2013-07-19', 'Investigadores paranormales enfrentan una presencia oscura.', 'terror', 'TheConjuring.jpg', 0),
('It', 'Andy Muschietti', '2017-09-08', 'Un grupo de niños enfrenta a un payaso aterrador.', 'terror', 'It.jpg', 0),
('Get Out', 'Jordan Peele', '2017-02-24', 'Un hombre afroamericano descubre un oscuro secreto familiar.', 'terror', 'GetOut.jpg', 0),
('La La Land', 'Damien Chazelle', '2016-12-09', 'Un pianista y una actriz luchan por sus sueños en Los Ángeles.', 'musical', 'LaLaLand.jpg', 0),
('The Greatest Showman', 'Michael Gracey', '2017-12-20', 'La historia de P.T. Barnum y su espectáculo circense.', 'musical', 'Showman.jpg', 0),
('A Star Is Born', 'Bradley Cooper', '2018-10-05', 'Una joven cantante y un músico luchan por el éxito.', 'musical', 'Born.jpg', 0);
>>>>>>>>> Temporary merge branch 2
>>>>>>> 805d5c417ba756f81691797f2a91f2645421753b
