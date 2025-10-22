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

    <img src="/src/img/soporte/vive_fime.png" alt="Vive la FIME">
</div>

<main class="soporteWrapper">

    <?php
    $i = 0;
        while ($fila = mysqli_fetch_assoc($resultado)):
            $primerNombre = strtolower(strtr(explode(" ", $fila['nombre'])[0], ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u']));
            $paginas = ["https://www.fime.uanl.mx/preguntas-frecuentes/", 
                        "/src/pdf/Manual_usuario_proy-pmbok.pdf", 
                        "https://www.youtube.com/playlist?list=PL-_ObXiitJQCjOBAbjiGWTisOOERwdDf5",
                        "https://www.fime.uanl.mx/buzon-de-sugerencias/",
                        "https://www.fime.uanl.mx/contacto/",
                        "https://www.google.com/maps/place/Faculty+of+Mechanical+and+Electrical+Engineering+UANL/@25.7253908,-100.3137887,15z/data=!4m5!3m4!1s0x86629452551ea79f:0x66e03550ec5730cb!8m2!3d25.7253908!4d-100.3055993"];
    ?>
        <a href="<?php echo $paginas[$i]; ?>" target="_blank">
            <div class="soporte">
                <h3><?php echo $fila['nombre'];?></h3>
                <img src="<?php echo '/src/img/soporte/' . $primerNombre . '.png';?>" alt="<?php echo $fila['nombre'];?>">
                <div class="info__texto"><h4>Ver más</h4></div>
            </div>
        </a>     
    <?php    
        $i++;  
        endWhile;
    ?>

</main>

 <?php include_once '../templates/footer.php'; ?>