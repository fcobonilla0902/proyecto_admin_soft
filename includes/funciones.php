<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Función que revisa que el usuario este autenticado
function isAuth()  {
    // 1. Asegurarse de que la sesión esté iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 2. Verificar si la variable de autenticación existe
    if (isset($_SESSION['alumno'])) {
        return true;
    } else {
        return false;
    } 
}

