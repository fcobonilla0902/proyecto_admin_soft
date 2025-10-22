<?php  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {     
    include '../../includes/database.php';      
    include '../../includes/funciones.php';     

    if (session_status() === PHP_SESSION_NONE) {         
        session_start();     
    }     
    isAuth();      

    $data = json_decode(file_get_contents('php://input'), true);     
    $porcentaje_nuevo = isset($data['porcentaje']) ? (int)$data['porcentaje'] : 0;       
    $identificador = $_SESSION['alumno']['identificador'] ?? null;     
    $modulo = 2;       

    if ($identificador) {         
        $query_check = "SELECT porcentaje FROM records_modulos WHERE identificador = ? AND modulo = ?";         
        $stmt_check = $db->prepare($query_check);         
        $stmt_check->bind_param("ss", $identificador, $modulo);         
        $stmt_check->execute();         
        $result = $stmt_check->get_result();         
        $registro_existente = $result->fetch_assoc();         
        $stmt_check->close();          

        $exito = false;         
        $accion = '';          

        if ($registro_existente) {             
            $porcentaje_actual_db = (int)$registro_existente['porcentaje'];             
            $porcentaje_a_guardar = max($porcentaje_actual_db, $porcentaje_nuevo);                          
            if ($porcentaje_a_guardar > $porcentaje_actual_db) {                 
                $query = "UPDATE records_modulos SET porcentaje = ? WHERE identificador = ? AND modulo = ?";                 
                $stmt = $db->prepare($query);                 
                $stmt->bind_param("iss", $porcentaje_a_guardar, $identificador, $modulo);                 
                $exito = $stmt->execute();                 
                $accion = 'actualizado';                 
                $stmt->close();             
            } else {                 
                $exito = true;                 
                $accion = 'sin_cambios';             
            }                      
        } else {             
            $query = "INSERT INTO records_modulos (identificador, modulo, porcentaje) VALUES (?, ?, ?)";             
            $stmt = $db->prepare($query);             
            $stmt->bind_param("ssi", $identificador, $modulo, $porcentaje_nuevo);             
            $exito = $stmt->execute();             
            $accion = 'insertado';             
            $stmt->close();         
        }          

        header('Content-Type: application/json');         
        if ($exito) {             
            echo json_encode(['status' => 'ok', 'guardado' => $porcentaje_nuevo, 'accion' => $accion]);         
        } else {             
            echo json_encode(['error' => 'Error al guardar', 'detalle' => $db->error, 'accion' => $accion]);         
        }     
    } else {         
        header('Content-Type: application/json');         
        echo json_encode(['error' => 'Identificador de alumno no encontrado.']);     
    }          
    exit; 
}  

include '../../includes/database.php';  
include '../../includes/funciones.php'; 
if (session_status() === PHP_SESSION_NONE) {     
    session_start(); 
} 
isAuth();  

$identificador = $_SESSION['alumno']['identificador'] ?? ''; 
$modulo = 2; 
$porcentaje_inicial = 0;  

if ($identificador) {     
    if (isset($db)) {          
        $query_select = "SELECT porcentaje FROM records_modulos WHERE identificador = ? AND modulo = ?";         
        $stmt_select = $db->prepare($query_select);         
        $stmt_select->bind_param("ss", $identificador, $modulo);         
        $stmt_select->execute();         
        $result = $stmt_select->get_result();         
        if ($row = $result->fetch_assoc()) {             
            $porcentaje_inicial = (int)$row['porcentaje'];         
        }         
        $stmt_select->close();     
    } 
}   

$titulo = "Módulo 2"; 
include_once '../../templates/header.php';  
?> 

<link rel="stylesheet" href="/src/css/modulos/ia.css">

<main class="main-container" id="main-content">    
    <div id="block">        
        <a href="/views/industria.php?op=2" class="back-arrow-link">            
            <img src="/src/img/flecha_back.png" alt="Volver a Industria 4.0">        
        </a>    
    </div>    

    <div id="progress-header">        
        <h2><?php echo $titulo . " - Inteligencia Artificial (IA)";?></h2> 
        <div id="progress-info">            
            <div id="scroll-progress-container">                
                <div id="progress-bar" style="width: <?php echo $porcentaje_inicial; ?>%;"></div>            
            </div>            
            <div id="percentage-display"><?php echo $porcentaje_inicial; ?>%</div>        
        </div>    
    </div>    

    <h2>¿Qué es la Inteligencia Artificial?</h2>
    <hr>
    <p>La <span class="resaltado">Inteligencia Artificial (IA)</span> es la rama de la informática que busca que las máquinas imiten procesos propios del pensamiento humano, como aprender, razonar, resolver problemas, percibir el entorno y adaptarse a él. Su objetivo es crear sistemas que puedan tomar decisiones informadas sin depender completamente de la intervención humana.</p>

    <p>Actualmente, la IA se encuentra en el corazón de la transformación digital global. Desde los algoritmos de recomendación de Netflix o Spotify, hasta los sistemas de conducción autónoma de Tesla, la IA se utiliza para optimizar recursos, mejorar la experiencia del usuario y descubrir patrones imposibles de detectar manualmente.</p>

    <div class="image-container">
        <img src="/src/img/modulos/ia/imagen1.jpg" alt="Representación de una mente digital conectada a una red de datos" class="ia_img large">
        <small>La IA combina grandes volúmenes de datos, algoritmos avanzados y poder de cómputo para simular capacidades cognitivas humanas.</small>
    </div>

    <h3>Tipos de Inteligencia Artificial</h3>
    <ul>
        <li><span class="resaltado">IA Débil:</span> Diseñada para realizar tareas específicas, generalmente una sola tarea. <br><span><span class="resaltado">Ejemplos:</span> El algoritmo de búsqueda de Google o el reconocimiento facial del iPhone.</span></li> <br>
        <li><span class="resaltado">IA Fuerte:</span> Sistemas que pueden entender, razonar, aprender y tomar decisiones de manera similar a un humano.<br><span><span class="resaltado">Ejemplos:</span> Agentes de diagnóstico médico y optimización de procesos industriales.</span></li> <br>
        <li><span class="resaltado">Superinteligencia:</span> Supera ampliamente las capacidades humanas, resolviendo problemas complejos con gran velocidad y precisión. <br><span><span class="resaltado">Ejemplos:</span> Exploración espacial avanzada y Ciberseguridad impenetrable.</span></li> <br>
    </ul>

    <hr>

    <h2>Áreas Clave de la Inteligencia Artificial</h2>

    <div class="component-section">
        <h3>1. Aprendizaje Automático (Machine Learning)</h3>
        <p>Permite que las máquinas aprendan de los datos sin ser programadas explícitamente. A través de algoritmos, identifican patrones y realizan predicciones o decisiones. Es la base de muchos sistemas actuales de IA.</p>

        <p>Dentro de esta área se encuentra lo que se conoce como <span class="resaltado">ciclo del machine learning</span>, lo que este hace es establecer una estructura al proyecto
            de machine learning y dividir de manera eficaz los recursos utilizados, creando modelos rentables y de calidad.</p>
        <br>
        <p>El ciclo de machine learning consta de 4 etapas:</p>
        <p><span class="resaltado">1. Ingreso de datos:</span> En esta sección se realiza un recopilado, etiquetado y limpieza de datos con los que alimentaremos nuestro modelo de machine learning,
            es fundamental debido a que nos da la base para realizar el entrenamiento y fortalecer nuestro modelo.</p>
        <p><span class="resaltado">2. Desarrollo del modelo:</span> Una vez que están listos los datos, el área de ciencia de datos comienza la construcción del modelo, esta fase consta de la creación o selección de algoritmos adecuados, 
            la fase de entrenamiento donde se alimenta de datos al algoritmo para que aprenda a identificar patrones y relaciones, y la evaluación del modelo funcional, generalmente con un conjunto de datos extra para tener una evaluación certera.</p>
        <p><span class="resaltado">3. Modelo puesto en práctica:</span> En esta fase, el modelo entrenado y evaluado se implementa en un entorno real. Esto significa integrarlo en una aplicación, un sitio web o un sistema de producción para que comience a hacer predicciones con datos nuevos y en tiempo real. 
            El objetivo es que el modelo empiece a generar valor y a cumplir la función para la que fue diseñado.</p>
        <p><span class="resaltado">4. Test y análisis:</span> Una vez que el modelo está en funcionamiento, no se puede simplemente olvidar. Es vital monitorear su rendimiento de forma continua.
            Esto se realiza por medio de tests que verifican que el modelo siga funcionando de manera correcta y análisis de resultados en períodos de tiempo específicos.</p>
        <p>Ejemplo real: <span class="resaltado">Amazon</span> utiliza Machine Learning para predecir qué productos interesarán a cada usuario y optimizar la logística de envío anticipando la demanda.</p>
        <img src="/src/img/modulos/ia/imagen2.png" alt="Diagrama del proceso de entrenamiento de Machine Learning" class="ia_img">
    </div>

    <div class="component-section">
        <h3>2. Redes Neuronales Artificiales y Deep Learning</h3>
        <p>Inspiradas en el funcionamiento del cerebro humano, las <span class="resaltado">redes neuronales</span> procesan información mediante capas de nodos interconectados. El <span class="resaltado">Deep Learning</span> usa redes con muchas capas para aprender de datos complejos, como imágenes o voz.</p>
        <p>Las redes neuronales constan de diferentes <span class="resaltado">capas interconectadas</span>, cada una con un propósito y función que generan respuestas útiles, el modelo más común es el de capa de entrada, oculta y de salida.</p>
        <ul>
            <li><span class="resaltado">Capa de entrada (Input Layer):</span> Su función principal es recibir y formatear los datos en bruto para ser interpretados.</li>
            <li><span class="resaltado">Capa oculta (Hidden Layer):</span> Pueden existir múltiples capas ocultas, su función principal es realizar cálculos y transformaciones complejas. Identifican características, patrones y relaciones complejas entre los datos</li>
            <li><span class="resaltado">Capa de salida (Output Layer):</span> Su función es producir el resultado final o la predicción de la red neuronal, presentando un análisis conclusivo en un formato útil.</li>
        </ul>
        <p>Ejemplo real: <span class="resaltado">Tesla</span> emplea redes neuronales profundas para que sus autos autónomos interpreten el entorno y tomen decisiones en tiempo real.</p>
        <img src="/src/img/modulos/ia/imagen3.png" alt="Red neuronal artificial" class="ia_img">
    </div>

    <div class="component-section">
        <h3>3. Procesamiento del Lenguaje Natural (NLP)</h3>
        <p>Es una rama de la inteligencia artificial que le da a las computadoras la capacidad de entender, interpretar y generar lenguaje humano (texto y voz) de una manera útil. 
            En esencia, es la tecnología que cierra la brecha entre la comunicación humana y la comprensión de las máquinas.</p>
        <h4>¿Cómo funciona?</h4>
        <p>El NLP combina la <span class="resaltado">lingüística computacional</span> con modelos de machine learning y deep learning. El proceso se puede dividir en dos grandes fases:</p>
            <ol>
                <li><span class="resaltado">Comprensión del Lenguaje Natural.</span> La máquina "lee" o "escucha" con el objetivo de extraer el significado del lenguaje humano, realizando análisis gramaticales y reconocimiento de intenciones.</li>
                <li><span class="resaltado">Generación del Lenguaje Natural.</span> La máquina "escribe" o "habla" para construir una respuesta coherente a lo comprendido, generando una salida en lenguaje humano.</li>
            </ol>
        <p>Ejemplo real: <span class="resaltado">OpenAI</span> desarrolla modelos como ChatGPT que pueden mantener conversaciones coherentes, responder preguntas o generar contenido textual.</p>
        <img src="/src/img/modulos/ia/imagen4.png" alt="Representación de chatbot y comunicación humano-máquina" class="ia_img">
    </div>

    <div class="component-section">
        <div class="component-content-wrapper">
            <div class="component-text">
                <h3>4. Visión Computacional</h3>
                <p>Este componente permite que las computadoras tengan la capacidad de <span class="resaltado">ver, entender e interpretar el contenido de imágenes o videos</span>, teniendo la capacidad de tomar decisiones o realizar acciones en base al contenido visualizado.</p>
                <h4>Funcionamiento</h4>
                <p>Los modelos de visión computacional funcionan en base a modelos de machine learning y redes neuronales para procesas cantidades masivas de datos. Este modelo de procesamiento se divide en 4 partes:</p>
                <ol>
                    <li><span class="resaltado">Adquisición de la imagen:</span> Una cámara o sensor captura una imagen o un video.</li> <br>
                    <li><span class="resaltado">Procesamiento:</span> La computadora analiza la imagen a nivel de píxeles.</li> <br>
                    <li><span class="resaltado">Extracción de características:</span> El sistema busca patrones, formas, colores, bordes y texturas relevantes en la imagen para identificar sus componentes.</li> <br>
                    <li><span class="resaltado">Interpretación:</span> Utilizando un modelo previamente entrenado, la computadora clasifica los objetos identificados. Compara los patrones extraídos con los que aprendió durante su entrenamiento</li>
                </ol>
                <p>Ejemplo real: <span class="resaltado">Google Photos</span> utiliza visión por computadora para clasificar automáticamente millones de imágenes por rostros, objetos o lugares.</p>
            </div>

             <div class="component-image">
                <div class="image-container half-width">
                    <img src="/src/img/modulos/ia/imagen5.jpg" alt="Muestra visual del concepto" class="ia_img">
                </div>
                <br>
                <div class="image-container half-width">
                    <img src="/src/img/modulos/ia/imagen6.jpg" alt="Muestra visual del concepto" class="ia_img">
                </div>
            </div>
        </div>    
    </div>

    <hr>

    <h2>Aplicaciones de la Inteligencia Artificial</h2>
    <p>La IA tiene aplicaciones en prácticamente todos los sectores. Algunos ejemplos son:</p>
    <ul>
        <li><span class="resaltado">Salud:</span> IBM Watson asiste a médicos en diagnósticos analizando miles de artículos científicos en segundos.</li> <br>
        <li><span class="resaltado">Educación:</span> plataformas como Coursera y Duolingo usan IA para adaptar los contenidos al progreso de cada estudiante.</li> <br>
        <li><span class="resaltado">Industria:</span> Siemens utiliza IA para predecir fallos en maquinaria y optimizar la producción.</li> <br>
        <li><span class="resaltado">Finanzas:</span> bancos como BBVA y JP Morgan aplican IA para detectar fraudes en tiempo real y analizar riesgos crediticios.</li> <br>
        <li><span class="resaltado">Marketing:</span> Netflix y YouTube personalizan recomendaciones para cada usuario gracias a modelos de aprendizaje profundo.</li> <br>
    </ul>

    <div class="image-container half-width">
        <img src="/src/img/modulos/ia/imagen7.jpg" alt="IA en la industria" class="ia_img">
        <img src="/src/img/modulos/ia/imagen8.jpg" alt="IA en salud y educación" class="ia_img">
    </div>

    <br><br>
</main>

<script>
let maxPercentage = <?php echo $porcentaje_inicial; ?>;
function scrollToPercentage(percentage) {
    const mainElement = document.getElementById('main-content');
    if (!mainElement || percentage === 0) return;
    const scrollHeight = mainElement.scrollHeight;
    const clientHeight = mainElement.clientHeight;
    const totalScrollableHeight = scrollHeight - clientHeight;
    if (totalScrollableHeight > 0) {
        const targetScrollTop = (percentage / 100) * totalScrollableHeight;
        mainElement.scrollTo({ top: targetScrollTop, behavior: 'auto' });
    }
}
document.getElementById('percentage-display').textContent = maxPercentage + '%';
document.getElementById('progress-bar').style.width = maxPercentage + '%';
function updateScrollPercentage() {
    const mainElement = document.getElementById('main-content');
    if (!mainElement) return;
    const scrollTop = mainElement.scrollTop;
    const scrollHeight = mainElement.scrollHeight;
    const clientHeight = mainElement.clientHeight;
    const totalScrollableHeight = scrollHeight - clientHeight;
    let percentage = 0;
    if (totalScrollableHeight > 0) {
        percentage = (scrollTop / totalScrollableHeight) * 100;
    }
    if (scrollTop + clientHeight >= scrollHeight - 2) {
        percentage = 100;
    }
    percentage = Math.min(100, Math.max(0, percentage));
    const currentRoundedPercentage = Math.round(percentage);
    if (currentRoundedPercentage > maxPercentage) {
        maxPercentage = currentRoundedPercentage;
        saveProgress(maxPercentage);
    }
    document.getElementById('percentage-display').textContent = maxPercentage + '%';
    document.getElementById('progress-bar').style.width = maxPercentage + '%';
}
function saveProgress(porcentaje) {
    fetch(window.location.href, { 
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ porcentaje })
    })
    .then(res => res.json())
    .then(data => console.log('Guardado:', data))
    .catch(err => console.error('Error guardando progreso:', err));
}
document.getElementById('main-content').addEventListener('scroll', updateScrollPercentage);
window.addEventListener('load', () => {
    updateScrollPercentage();
    scrollToPercentage(maxPercentage);
});
</script>

<?php include_once '../../templates/footer.php'; ?> 
