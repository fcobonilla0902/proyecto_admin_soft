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

    if($juego==3){
        $titulo = "Circuitos Mágicos"; 
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

<link rel="stylesheet" href="/src/css/juegos/circuitos.css">

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

        <h1 id="level-title">Nivel 1: Compuerta AND</h1>
        <div id="circuit-board">
            <div class="input-section">
                <div class="switch-container">
                    <label class="switch">
                        <input type="checkbox" id="input1">
                        <span class="slider"></span>
                    </label>
                    <span>Entrada A</span>
                </div>
                <div class="switch-container" id="input-container-2">
                    <label class="switch">
                        <input type="checkbox" id="input2">
                        <span class="slider"></span>
                    </label>
                    <span>Entrada B</span>
                </div>
            </div>

            <div class="gate-section">
                <img id="gate-image" src="https://www.svgrepo.com/show/369792/and-gate.svg" alt="Compuerta Lógica">
            </div>

            <div class="output-section">
                <div id="led" class="led-off"></div>
                <span>Salida</span>
            </div>
        </div>
        <div id="instructions">
            <p>Mueve los interruptores para encender el LED.</p>
        </div>
        <div id="win-message" class="hidden">
            <p>¡Correcto! Has encendido el LED.</p>
            <button id="next-level-btn">Siguiente Nivel</button>
        </div>
    </div>
<?php
    } 
    else {
?>  
    <p class="ya_jugaste">Ya ganaste este juego, quieres volver a jugarlo?</p>
    <a id="next-btn2" href="circuitos.php?juego=3&again=1">Jugar de nuevo</a>
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
                <p>¡Felicidades, has aprendido las compuertas lógicas!</p>
                <button id="play-again-btn">Jugar de nuevo</button>
            </div>
        </div>
    </div>

</main>

<script src="/src/js/juegos/circuitos.js"></script>

<?php 
// Incluimos el footer
include_once '../../templates/footer.php'; 
?>
