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
    $i = 0;
        while ($fila = mysqli_fetch_assoc($resultado)):
            $primerNombre = strtolower(strtr(explode(" ", $fila['nombre'])[0], ['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u']));
            $paginas = ["https://www.sap.com/latinamerica/products/scm/industry-4-0/what-is-industry-4-0.html", 
                        "https://mesbook.com/aplicaciones-industria-4-0/", 
                        "https://www.occ.com.mx/empleos/de-industria-4.0/en-mexico/",
                        "https://altertecnia.com/7-empleos-de-futuro-en-la-industria-4-0/"];
                      
    ?>
        <a href="<?php echo $paginas[$i]; ?>" target="_blank">
            <div class="info"> 
                <img src="<?php echo '/src/img/inicio/' . $primerNombre . '.png';?>" alt="<?php echo $fila['nombre']; ?>">
                <div class="info__texto"><h4><?php echo $fila['nombre']; ?></h4></div>
            </div>
        </a>     
    <?php   
        $i++;  
        endWhile;
    ?>

</main>

 <?php include_once 'templates/footer.php'; ?>