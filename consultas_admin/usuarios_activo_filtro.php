<?php
require_once "../conexion.php";

$filter_nombre = isset($_POST['filter_nombre']) ? $_POST['filter_nombre'] : '';
$filter_email  = isset($_POST['filter_email'])  ? $_POST['filter_email']  : '';
$filter_rol    = isset($_POST['filter_rol'])    ? $_POST['filter_rol']    : '';

$query = "SELECT * FROM Usuarios WHERE estado = 'activo'";

if (!empty($filter_nombre)) {
    $query .= " AND nombre LIKE :nombre";
}
if (!empty($filter_email)) {
    $query .= " AND email LIKE :email";
}
if (!empty($filter_rol)) {
    $query .= " AND es_admin = :rol";
}

$stmt = $conn->prepare($query);

if (!empty($filter_nombre)) {
    $nombre = "%$filter_nombre%";
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
}
if (!empty($filter_email)) {
    $email = "%$filter_email%";
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
}
if (!empty($filter_rol)) {
    $stmt->bindParam(':rol', $filter_rol, PDO::PARAM_STR);
}

$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($usuarios);
