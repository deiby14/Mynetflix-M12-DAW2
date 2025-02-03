<?php
session_start();
require_once 'conexion.php';

$isLoggedIn = isset($_SESSION['usuario']);
$userId = $isLoggedIn ? $_SESSION['usuario']['id'] : null;
?> 