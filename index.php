<?php 
include 'includes/funciones.php';
include 'includes/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$titulo = "Inicio";

$query = "SELECT * FROM inicio"; 
$resultado = mysqli_query($db, $query);
 
include_once 'templates/header.php'; 
?>
<link rel="stylesheet" href="src/css/menu/inicio.css">

<main class="inicioWrapper">
    <?php
        while ($fila = mysqli_fetch_assoc($resultado)):
            $primerNombre = strtolower(strtr(explode(" ", $fila['nombre'])[0], ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u']));
    ?>
        <a href="#">
            <div class="info">
                <img src="<?php echo '/src/img/inicio/' . $primerNombre . '.png';?>" alt="<?php echo $fila['nombre']; ?>">
                <div class="info__texto"><h4><?php echo $fila['nombre']; ?></h4></div>
            </div>
        </a>     
    <?php    
        endWhile;
    ?>

</main>

 <?php include_once 'templates/footer.php'; ?>