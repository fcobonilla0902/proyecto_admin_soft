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

    if($juego==5){
        $titulo = "Big Data Puzzles"; 
        $op = 3;
    }
}

if(isset($_GET['again'])){
    $again = 1;
}

$query = "SELECT * FROM records_juegos WHERE identificador = '$identificador' AND juego = '$juego'";
$resultado = mysqli_query($db, $query);

// Incluimos el header
include_once '../../templates/header.php';
?>

<link rel="stylesheet" href="/src/css/juegos/big.css">

<main class="content-area-juego">
    <a href="/views/juegos.php?op=3" class="back-arrow-link">
        <img src="/src/img/flecha_back.png" alt="Volver a Juegos">
    </a>

    <div id="game-container">
        <div class="game-header">
            <h2><?php echo $titulo; ?></h2>
        </div>

<?php
    if($resultado->num_rows === 0 || $again === 1){
?>
        <div class="game-body">
            <div id="puzzle-area">
                <p id="scenario"></p>
                <div id="pipeline-container" class="drop-zone"></div>
            </div>

            <p class="instructions">Arrastra los pasos al flujo de datos de arriba en el orden correcto.</p>
            
            <div id="source-blocks" class="blocks-container"></div>

            <div class="action-buttons">
                <button id="check-btn">Comprobar</button>
                <button id="next-btn" class="hide">Siguiente</button>
            </div>
            <div id="feedback"></div>
        </div>

<?php
    } 
    else {
?>  
    <p class="ya_jugaste">Ya ganaste este juego, quieres volver a jugarlo?</p>
    <a id="next-btn2" href="big.php?juego=5&again=1">Jugar de nuevo</a>
    <br><br>
<?php
    }
?>
    </div>

    <div id="win-modal" class="modal-overlay hide">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo $titulo; ?></h2>
            </div>

            <div class="modal-body">
                <h2>¡Ganaste!</h2>
                <p>¡Felicidades, has resuelto todos los puzzles!</p>
                <button id="play-again-btn">Jugar de nuevo</button>
            </div>
        </div>
    </div>

</main>


<script src="/src/js/juegos/big.js"></script>

<?php 
// Incluimos el footer
include_once '../../templates/footer.php'; 
?>