<?php 
include '../includes/funciones.php';
include '../includes/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
isAuth();

$titulo = "Asistente IA";
$op = isset($_GET['op']) ? (int)$_GET['op'] : 5;

include_once '../templates/header.php'; 
?>

<link rel="stylesheet" href="../src/css/comunidad.css">

<main class="content" style="padding: 20px; display: flex; flex-direction: column; height: calc(100% - 120px);">
    <div id="chat-container" style="flex: 1; overflow-y: auto; background: #f7f7f7; border: 1px solid #ddd; border-radius: 8px; padding: 16px;">
        <div class="message bot" style="margin-bottom: 12px;">
            <strong>Bot:</strong> Hola, soy tu asistente de IA. ¿En qué puedo ayudarte?
        </div>
    </div>

    <form id="chat-form" style="display: flex; gap: 8px; margin-top: 12px;" onsubmit="return false;">
        <input id="chat-input" type="text" placeholder="Escribe tu mensaje..." style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 6px;" />
        <button id="chat-send" type="submit" style="padding: 10px 16px; background: #0a4624; color: #fff; border: none; border-radius: 6px; cursor: pointer;">Enviar</button>
    </form>
</main>

<?php include_once '../templates/footer.php'; ?>

<script type="module" src="/src/js/ai/chat.js"></script>


