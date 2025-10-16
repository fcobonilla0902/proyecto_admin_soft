<?php 
include '../includes/funciones.php';
include '../includes/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
isAuth();

$titulo = "Acerca de";

$query = "SELECT * FROM soporte";
$resultado = mysqli_query($db, $query);

include_once '../templates/header.php'; ?>

<link rel="stylesheet" href="../src/css/acerca.css">

<div class="header">
    <h2 class="titulo_soporte"><?php echo $titulo;?></h2>

    <img src="/src/img/soporte/vive_fime.png" alt="Vive la FIME">
</div>

<main class="main-container" id="main-content">
    <div class="acerca-seccion">
        <h2>Nuestra Misión</h2>
        <p>Capacitar a profesionales, estudiantes y entusiastas con las habilidades necesarias para navegar y liderar la <span class="resaltado">Cuarta Revolución Industrial</span>. Ofrecemos un ecosistema de aprendizaje dinámico que combina teoría, práctica y soporte comunitario, enfocándonos en la <span class="resaltado">Industria 4.0</span> y sus tecnologías clave.</p>
    </div>
    
    <hr>

    <div class="acerca-seccion">
        <h2>Componentes Clave de Aprendizaje</h2>
        <p>Hemos integrado diversas herramientas para garantizar una experiencia de aprendizaje completa y efectiva:</p>
        
        <div class="acerca-lista">
            
            <h3>1. Módulos de Lectura Interactiva</h3>
            <p>Accede a contenido educativo estructurado y actualizado. Nuestros módulos desglosan conceptos complejos de la Industria 4.0 (como IoT, Big Data, Robótica, Ciberseguridad) en lecciones claras y concisas.</p>
            <ul>
                <li><span class="resaltado">Enfoque:</span> Aprende a tu propio ritmo, desde los fundamentos hasta las aplicaciones avanzadas.</li>
                <li><span class="resaltado">Contenido:</span> Incluye explicaciones detalladas, ejemplos del mundo real y casos de estudio.</li>
            </ul>

            <h3>2. Asistente de Inteligencia Artificial (IA)</h3>
            <p>Tu compañero de estudio siempre disponible. Nuestro asistente de IA está entrenado con vastos recursos de la Industria 4.0 para proporcionarte respuestas inmediatas y contextualizadas.</p>
            <ul>
                <li><span class="resaltado">Soporte 24/7:</span> Resuelve dudas técnicas y conceptuales al instante.</li>
                <li><span class="resaltado">Personalización:</span> Adapta las explicaciones a tu nivel de conocimiento, ya seas principiante o experto.</li>
            </ul>

            <h3>3. Juegos y Simulaciones de Aprendizaje</h3>
            <p>Convierte el aprendizaje en una actividad atractiva. Usamos la <span class="resaltado">gamificación</span> para reforzar conceptos clave y probar tus habilidades de toma de decisiones en entornos industriales simulados.</p>
            <ul>
                <li><span class="resaltado">Aprendizaje Activo:</span> Aplica teoría para resolver problemas en entornos virtuales.</li>
                <li><span class="resaltado">Habilidades Prácticas:</span> Desarrolla pensamiento crítico en la gestión de ciberseguridad industrial y la optimización de líneas de producción.</li>
            </ul>

            <h3>4. Foro de Comunidad y Colaboración</h3>
            <p>Conéctate con otros estudiantes y expertos. El foro es el corazón de nuestra plataforma, un espacio donde puedes intercambiar ideas, buscar mentores y discutir tendencias emergentes.</p>
            <ul>
                <li><span class="resaltado">Networking:</span> Establece contactos con profesionales de la industria.</li>
                <li><span class="resaltado">Soporte Mutuo:</span> Obtén ayuda con proyectos, comparte recursos y participa en debates enriquecedores.</li>
            </ul>

        </div>
    </div>

    <hr>
    
    <div class="acerca-seccion final">
        <h2>¿Por qué usar esta página?</h2>
        <ul>
            <li><span class="resaltado">Relevancia:</span> Contenido enfocado <span class="resaltado">100%</span> en las demandas actuales del mercado laboral de la Industria 4.0.</li>
            <li><span class="resaltado">Innovación:</span> Herramientas impulsadas por <span class="resaltado">IA</span> que maximizan tu eficiencia de estudio.</li>
            <li><span class="resaltado">Comunidad:</span> Un espacio colaborativo que garantiza que nunca estudies solo.</li>
        </ul>
        <p class="llamada-accion"><span class="resaltado">¡Únete a la Cuarta Revolución Industrial y transforma tu carrera hoy mismo!</span></p>
    </div>

</main>

 <?php include_once '../templates/footer.php'; ?>