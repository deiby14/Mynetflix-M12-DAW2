<?php
session_start();

$isLoggedIn = isset($_SESSION['usuario']);

// Si estamos en index.php y el usuario es admin, redirigir al panel de admin
if (basename($_SERVER['PHP_SELF']) === 'index.php') {
    if ($isLoggedIn && isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'admin') {
        header('Location: admin/dashboard.php');
        exit();
    }
}

// Si estamos en una página de admin
if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    // Verificar que el usuario esté logueado y sea admin
    if (!$isLoggedIn || !isset($_SESSION['usuario']['rol']) || $_SESSION['usuario']['rol'] !== 'admin') {
        header('Location: ../login.php');
        exit();
    }
}
?>