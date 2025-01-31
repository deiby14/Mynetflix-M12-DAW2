
<?php

include 'conexion.php';



if (isset($_POST['nombre'], $_POST['email'], $_POST['estado'], $_POST['es_admin'], $_POST['contrasena'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $estado = $_POST['estado'];
    $es_admin = $_POST['es_admin'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare("INSERT INTO Usuarios (nombre, email, estado, es_admin, contrasena) VALUES (:nombre, :email, :estado, :es_admin, :contrasena)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':es_admin', $es_admin);
        $stmt->bindParam(':contrasena', $contrasena);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usuario añadido con éxito']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo añadir el usuario']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>
