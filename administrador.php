<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Plataforma de Streaming</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            
        background-image: url("./img/fondo.jpg");
        color: #fff;
        }
        .fondo {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
            display: flex;
            
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
            width: 300px;
            height: auto;
        }
        .image-container p {
            color: #fff;
            
            font-weight: bold;
            font-size: 2em;
        }
    </style>
</head>
<body>
    <div class="fondo">
    </div>
    <div class="container">
        <h1 class="text-center my-5">Panel de Administración</h1>
        <div class="row">
            <div class="col-md-6 image-container">
                <a href="usuarios.php">
                    <img src="./img/Usuarios.png" alt="Usuarios" class="clickable-image">
                    <p>Usuarios</p>
                </a>
            </div>
            <div class="col-md-6 image-container">
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