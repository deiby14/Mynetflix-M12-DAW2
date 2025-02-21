<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Plataforma de Streaming</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #000;
            color: #fff;
        }
        .clickable-image {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .clickable-image:hover {
            transform: scale(1.05);
        }
        .image-container {
            text-align: center;
            margin-top: 50px;
        }
        .image-container img {
            width: 200px;
            height: auto;
        }
        .image-container p {
            margin-top: 10px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center my-5">Panel de Administración</h1>
        <div class="row">
            <div class="col-md-4 image-container">
                <a href="usuarios.php">
                    <img src="./img/Usuarios.png" alt="Usuarios" class="clickable-image">
                    <p>Usuarios</p>
                </a>
            </div>
            <div class="col-md-4 image-container">
                <a href="peliculas.php">
                    <img src="./img/Peli.png" alt="Películas" class="clickable-image">
                    <p>Películas</p>
                </a>
            </div>
          
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>