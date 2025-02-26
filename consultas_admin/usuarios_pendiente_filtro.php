<?php
require_once "../conexion.php";

$filter_nombre = isset($_POST['filter_nombre']) ? $_POST['filter_nombre'] : '';
$filter_email  = isset($_POST['filter_email'])  ? $_POST['filter_email']  : '';
$filter_estado = isset($_POST['filter_estado']) ? $_POST['filter_estado'] : ''; // Aunque la consulta filtra por pendiente

// Filtramos para mostrar únicamente los usuarios cuyo estado sea "pendiente"
$query = "SELECT * FROM Usuarios WHERE estado = 'pendiente'";

if (!empty($filter_nombre)) {
    $query .= " AND nombre LIKE :nombre";
}
if (!empty($filter_email)) {
    $query .= " AND email LIKE :email";
}
// Aunque el filtro de estado se envíe, la consulta fija el estado a 'pendiente'

$stmt = $conn->prepare($query);

if (!empty($filter_nombre)) {
    $nombre = "%$filter_nombre%";
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
}
if (!empty($filter_email)) {
    $email = "%$filter_email%";
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
}

$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($usuarios);
