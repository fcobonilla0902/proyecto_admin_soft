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

    if($juego==1){
        $titulo = "Trivia Industria 4.0";
        $op = 3;
    }
}

if(isset($_GET['again'])){
    $again = 1;
}

$query = "SELECT * FROM records_juegos WHERE identificador = '$identificador' AND juego = '$juego'";
$resultado = mysqli_query($db, $query);

include_once '../../templates/header.php';
?>
<link rel="stylesheet" href="/src/css/juegos/trivia.css">
<main class="content-area">
    <a href="/views/juegos.php?op=3" class="back-arrow-link">
        <img src="/src/img/flecha_back.png" alt="Volver a Juegos">
    </a>
    
    <div class="quiz-container" id="quiz">
        <div class="quiz-header">
            <h2><?php echo $titulo; ?></h2>
        </div>

        <div class="quiz-body">
<?php
    if($resultado->num_rows === 0 || $again === 1){
?>
            <div id="start-container">
                <h3>Pon a prueba tus conocimientos sobre la Industria 4.0.</h3>
                <p>Responde 10 preguntas y descubre tu puntuación.</p>
                <button id="start-btn">¡Comenzar!</button>
            </div>
            
            <div id="quiz-container" class="hidden">
                <p id="question"></p>
                <ul class="options" id="options-container"></ul>
                <div id="progress"></div>
                <button id="next-btn" class="hidden">Siguiente</button>
            </div>
<?php
    } 
    else {
?> 

    <p class="ya_jugaste">Ya ganaste este juego, quieres volver a jugarlo?</p>
    <a id="next-btn2" href="trivia.php?juego=1&again=1">Jugar de nuevo</a>
<?php
    }
?>
        </div>
    </div>

    <div id="win-modal" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo $titulo; ?></h2>
            </div>

            <div class="modal-body">
                <h2>¡Trivia completada!</h2>
                <p>¡Felicidades, has respondido todas las preguntas!</p>
                <p id="score"></p>
                <button id="restart-btn">Jugar de nuevo</button>
            </div>
        </div>
    </div>
        
</main>
<script src="/src/js/juegos/trivia.js"></script>

 <?php include_once '../../templates/footer.php'; ?>