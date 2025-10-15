<?php 
include '../includes/funciones.php';
include '../includes/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$auth = isAuth();

$titulo = "Juegos Interactivos";

$query = "SELECT * FROM juegos";
$resultado = mysqli_query($db, $query);

include_once '../templates/header.php'; 
?>

<link rel="stylesheet" href="../src/css/menu/juegos.css">

<h2 class="titulo_juegos"><?php echo $titulo;?></h2>

<?php
    if($auth == true){
?>
<main class="juegosWrapper">
    <?php
        while ($fila = mysqli_fetch_assoc($resultado)){
            $primerNombre = strtolower(explode(" ", $fila['nombre'])[0]);
    ?>
        <a href="juegos/<?php echo $primerNombre;?>.php?juego=<?php echo $fila['id'];?>">
            <div class="juego">
                <h3><?php echo $fila['nombre'];?></h3>
                <img src="<?php echo '/src/img/juegos/' . $primerNombre . '.png';?>" alt="<?php echo $fila['nombre'];?>">
                <div class="info__texto"><h4>Jugar</h4></div>
            </div>
        </a>     
    <?php    
        }
    }  else if($auth == false) {
    ?>
        <main class="juegosWrapper__no_login">
            <h3 class="no_login">Debes <a href="login.php">iniciar sesión</a> para poder acceder a los juegos</h3>
    <?php
        }
    ?>  
</main>



 <?php include_once '../templates/footer.php'; ?>