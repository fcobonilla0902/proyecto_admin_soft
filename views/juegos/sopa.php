<?php
session_start();
require_once '../../includes/database.php';
include '../../includes/funciones.php';

isAuth();


$identificador = $_SESSION['alumno']['identificador'];
$tipo = $_SESSION['tipo'];

$again = 0;


if(isset($_GET['juego'])){
    $juego = $_GET['juego'];

    if($juego==2){
        $titulo = "Sopa de Letras: IA y Robótica";
        $op = 3; 
    }
}

if(isset($_GET['again'])){
    $again = 1;
}

if(isset($_GET['h'])){
    $header = true;
    if($_GET['h'] == 0){
        $header = false;
    }
}


$query = "SELECT * FROM records_juegos WHERE identificador = '$identificador' AND juego = '$juego'";
$resultado = mysqli_query($db, $query);

include_once '../../templates/header.php';
?>

<link rel="stylesheet" href="/src/css/juegos/sopa.css">

<main class="content-area-juego">
    <a href="/views/juegos.php?op=3" class="back-arrow-link">
        <img src="/src/img/flecha_back.png" alt="Volver a Juegos">
    </a>
    

<?php

    if($resultado->num_rows === 0 || $again === 1){
?>
<div class="game-container" id="game-container">
        <h1 class="titulo"><?php echo $titulo; ?></h1>
        <div id="grid-container"></div>
    </div>

    <div id="words-container">
        <h2>Palabras a encontrar:</h2>
        <ul id="words-list"></ul>
    </div>

<?php
    } 
    else {
?>  
 <div class="game-container2">
    <div class="game-header">
        <h2><?php echo $titulo; ?></h2>
    </div>

    <p class="ya_jugaste">Ya ganaste este juego, quieres volver a jugarlo?</p>
    <a id="next-btn2" href="sopa.php?juego=2&again=1&h=0">Jugar de nuevo</a>
    <br><br>
<?php
    }
?>

    <div id="win-modal" class="modal-overlay hide">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo $titulo; ?></h2>
            </div>

            <div class="modal-body">
                <h2>¡Ganaste!</h2>
                <p>¡Felicidades, encontraste todas las palabras!</p>
                <button id="play-again-btn">Jugar de nuevo</button>
            </div>
        </div>
    </div>

</main>

<script src="/src/js/juegos/sopa.js"></script>

<?php 
include_once '../../templates/footer.php'; 
?>