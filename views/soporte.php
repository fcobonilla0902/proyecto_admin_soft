<?php 
include '../includes/funciones.php';
include '../includes/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
isAuth();

$titulo = "Soporte Técnico";
$tituloWrapper = "Centro de Ayuda y Soporte";

$query = "SELECT * FROM soporte";
$resultado = mysqli_query($db, $query);

include_once '../templates/header.php'; ?>

<link rel="stylesheet" href="../src/css/soporte.css">

<div class="header">
    <h2 class="titulo_soporte"><?php echo $tituloWrapper;?></h2>

    <img src="/src/img/vive_fime.png" alt="Vive la FIME">
</div>

<main class="soporteWrapper">

    <?php
        while ($fila = mysqli_fetch_assoc($resultado)):
            $primerNombre = strtolower(strtr(explode(" ", $fila['nombre'])[0], ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u']));
    ?>
        <a href="#">
            <div class="soporte">
                <h3><?php echo $fila['nombre'];?></h3>
                <img src="<?php echo '/src/img/' . $primerNombre . '.png';?>" alt="<?php echo $fila['nombre'];?>">
                <div class="info__texto"><h4>Ver más</h4></div>
            </div>
        </a>     
    <?php    
        endWhile;
    ?>

</main>

 <?php include_once '../templates/footer.php'; ?>