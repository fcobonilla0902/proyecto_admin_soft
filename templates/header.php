<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(isset($_GET['op'])){
    $op = $_GET['op'];
} else {
    $op = 1;
}

$alumno = isset($_SESSION['alumno']) ? $_SESSION['alumno'] : '';

$saludo_nombre = '';
if (!empty($alumno)) {
    // Tomamos solo la primera palabra del nombre
    $primer_nombre = explode(' ', $alumno['nombre'])[0];
    // Formateamos el saludo con una coma y el nombre
    $saludo_nombre = ', ' . htmlspecialchars($primer_nombre);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? ''; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/src/css/esqueleto.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div class="page-container">
        
        <aside class="sidebar">
            <div class="sidebar-top">
                <div class="user-profile">
                    <i class="fa-solid fa-user"></i>
                    <span>Bienvenido<?php echo $saludo_nombre;?></span>
                </div>
            </div>
            <div class="sidebar-middle">
                <img src="/src/img/oso_fime.jpg" alt="Oso FIME" class="main-logo">
                <h1 class="sidebar-title"><?php echo $titulo;?></h1>
            </div>
        </aside>

        <div class="content-wrapper">
            <header class="top-bar">
                <div class="logo-ecc">
                    <img src="/src/img/ecc_logo_sin_fondo.png" alt="Logo ECC">
                </div>
                <nav>
                    <a href="/index.php?op=1" class="<?php echo ($op == 1) ? 'active' : '';?>">Inicio</a>
                    <a href="/views/industria.php?op=2" class="<?php echo ($op == 2) ? 'active' : '';?>">Industria 4.0</a>
                    <a href="/views/juegos.php?op=3" class="<?php echo ($op == 3) ? 'active' : '';?>">Juegos Interactivos</a>
                    <a href="/views/comunidad.php?op=4" class="<?php echo ($op == 4) ? 'active' : '';?>">Comunidad</a>
                    <a href="/views/chat.php?op=5" class="<?php echo ($op == 5) ? 'active' : '';?>">Asistente IA</a>
                    <?php 
                        if($saludo_nombre != ''){
                    ?>
                            <a href="/views/logout.php">Cerrar Sesión</a>
                    <?php
                        }
                        else if($saludo_nombre == ''){
                    ?>
                        <a href="/views/login.php">Iniciar Sesión</a>
                    <?php
                        }
                    ?>
                </nav>
            </header>
