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
    $modulo = 6;       

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
$modulo = 6; 
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

$titulo = "Módulo 6"; 
include_once '../../templates/header.php';  
?> 


<link rel="stylesheet" href="/src/css/modulos/robot.css">

<main class="main-container" id="main-content">    
    <div id="block">        
        <a href="/views/industria.php?op=2" class="back-arrow-link">            
            <img src="/src/img/flecha_back.png" alt="Volver a Industria 4.0">        
        </a>    
    </div>    

    <div id="progress-header">        
        <h2><?php echo $titulo . " - Robótica Avanzada";?></h2> 
        <div id="progress-info">            
            <div id="scroll-progress-container">                
                <div id="progress-bar" style="width: <?php echo $porcentaje_inicial; ?>%;"></div>            
            </div>            
            <div id="percentage-display"><?php echo $porcentaje_inicial; ?>%</div>        
        </div>    
    </div>   
    
    <div class="content-section">
        <h2>¿Qué es la Robótica Avanzada?</h2>
        <p>La robótica avanzada se refiere al uso de robots altamente sofisticados y autónomos en diversas industrias para mejorar la eficiencia, precisión y seguridad en las operaciones. Estos robots están equipados con tecnologías avanzadas como inteligencia artificial, sensores, visión por computadora y capacidades de aprendizaje automático, lo que les permite adaptarse a entornos cambiantes y realizar tareas complejas.</p>
        <p>En el contexto de la Industria 4.0, la robótica avanzada juega un papel crucial al automatizar procesos de fabricación, logística y mantenimiento, lo que conduce a una mayor productividad y reducción de costos. Estos robots pueden trabajar junto con humanos en entornos colaborativos, mejorando la flexibilidad y capacidad de respuesta de las operaciones industriales.</p>

        <img src="/src/img/modulos/robot/imagen1.jpg" alt="Representación de robótica avanzada" class="robot.img" style="max-width: 600px; margin: 20px auto; margin-left: 200px;">

    </div>
    <hr>
    <h2>Componentes clave de un robot avanzado</h2>
    <div class="component-section">
        <h3>Funcionamiento conjunto de componentes</h3>
        <div class="content-section">
            <p>Los robots avanzados están compuestos por varios componentes clave que trabajan en conjunto para permitir su funcionamiento eficiente y autónomo. Estos componentes incluyen:</p>
            <ul>
                <li><strong>Sensores:</strong> Permiten al robot percibir su entorno, detectar obstáculos y recopilar datos necesarios para la toma de decisiones.</li>
                <li><strong>Actuadores:</strong> Son los mecanismos que permiten al robot moverse y realizar tareas físicas, como brazos robóticos o ruedas.</li>
                <li><strong>Unidad de procesamiento:</strong> Es el "cerebro" del robot, donde se ejecutan algoritmos de inteligencia artificial y se procesan los datos de los sensores.</li>
                <li><strong>Software de control:</strong> Incluye los programas y algoritmos que dirigen las acciones del robot, desde la navegación hasta la manipulación de objetos.</li>
                <li><strong>Sistemas de comunicación:</strong> Permiten al robot interactuar con otros sistemas, robots o humanos, facilitando la colaboración y el intercambio de información.</li>
            </ul>
            <p>El funcionamiento conjunto de estos componentes permite a los robots avanzados adaptarse a diferentes tareas y entornos, mejorando la eficiencia y seguridad en las operaciones industriales.</p>
        </div>

        <div class="image-container-width">
            <img src="/src/img/modulos/robot/imagen2.png" alt="Componentes de un robot avanzado" class="robot_img medium">
        </div>
    
    </div>
    <hr>
    <h2>Percepción robótica y fusión de sensores</h2>
    <div class="component-section">
        <div class="content-section">
            <h3>Percepción robótica</h3>
            <p>La percepción robótica es la capacidad de un robot para interpretar y comprender su entorno a través de la recopilación y procesamiento de datos sensoriales. Esto incluye el uso de diversos sensores, como cámaras, LIDAR, ultrasonidos y sensores táctiles, que permiten al robot detectar objetos, medir distancias y reconocer patrones en su entorno.</p>
            <p>Esta incluye conceptos como:</p>
            <ul>
                <li><span class="resaltado">Visión Computacional:</span> Como vimos en el módulo de IA, esta es una de las herramientas de percepción más importantes. Permite a los robots "ver" e identificar objetos, reconocer personas, leer texto y detectar obstáculos.</li>
                <li><span class="resaltado">LiDAR (Light Detection and Ranging):</span> Es un sensor crucial para la navegación. Emite pulsos de láser y mide el tiempo que tardan en rebotar, creando un mapa de puntos 3D de alta precisión del entorno. Es la tecnología clave en la mayoría de los coches autónomos.</li>
            </ul>
        </div>
    </div>

    <div class="component-section">
        <div class="component-content-wrapper">
            <div class="content-section">
                <h3>Fusión de sensores</h3>
                <p>La fusión de sensores es una técnica utilizada en robótica avanzada para combinar datos de múltiples sensores con el fin de obtener una representación más precisa y completa del entorno. Al integrar información de diferentes fuentes, los robots pueden mejorar su capacidad de percepción, reducir la incertidumbre y tomar decisiones más informadas.</p>
                <p>Por ejemplo, un robot puede utilizar datos de una cámara para identificar objetos visualmente, mientras que un sensor LIDAR proporciona información precisa sobre la distancia a esos objetos. Al fusionar estos datos, el robot puede crear un mapa detallado de su entorno, lo que mejora su capacidad para navegar y realizar tareas complejas de manera segura y eficiente.</p>
            </div>
            
            <div class="image-container-width">
                <img src="/src/img/modulos/robot/imagen3.jpg" alt="Fusión de sensores en robótica" class="robot_img medium">
            </div>
        </div>
    </div>

    <div class="component-section">
        <div class="content-section">
            <h3>Importancia de la percepción y fusión de sensores</h3>
            <p>La percepción robótica y la fusión de sensores son fundamentales para el funcionamiento efectivo de los robots avanzados. Estas capacidades permiten a los robots adaptarse a entornos dinámicos, mejorar su precisión en la ejecución de tareas y aumentar su autonomía. Al combinar datos de múltiples sensores, los robots pueden superar las limitaciones individuales de cada sensor, lo que resulta en una comprensión más robusta y confiable del entorno.</p>
            <p>En aplicaciones como la navegación autónoma, la manipulación de objetos y la interacción con humanos, la percepción precisa y la fusión de sensores son esenciales para garantizar la seguridad y eficiencia del robot. Estas tecnologías continúan evolucionando, impulsando avances significativos en el campo de la robótica avanzada.</p>
        </div>
    </div>
    <hr>


    <div class="component-section">
        <div class="component-content-wrapper">
            <div class="content-section">
                <h3>Navegación y localización</h3>
                <p>La navegación y localización son aspectos fundamentales de la robótica avanzada que permiten a los robots moverse de manera autónoma y precisa en su entorno. La navegación se refiere al proceso mediante el cual un robot planifica y sigue una ruta desde su posición actual hasta un destino objetivo, mientras que la localización implica determinar la posición exacta del robot dentro de un espacio dado.</p>
                <p>Para lograr una navegación efectiva, los robots utilizan una combinación de sensores, algoritmos de mapeo y técnicas de planificación de rutas. Los sensores recopilan datos sobre el entorno, que luego se procesan para crear mapas digitales que representan obstáculos, caminos y puntos de referencia. Los algoritmos de planificación de rutas analizan estos mapas para encontrar la mejor trayectoria hacia el destino, evitando colisiones y optimizando el tiempo de viaje.</p>
                <p>La localización precisa es crucial para la navegación exitosa. Los robots emplean técnicas como la localización basada en sensores (utilizando datos de cámaras, LIDAR, GPS, etc.) y métodos probabilísticos como el filtro de partículas o el filtro de Kalman para estimar su posición en tiempo real. Al combinar la navegación y la localización, los robots avanzados pueden operar de manera autónoma en entornos dinámicos y complejos, mejorando su eficiencia y capacidad para realizar tareas diversas.</p>
            </div>

            <div class="image-container-width">
                <img src="/src/img/modulos/robot/imagen4.jpg" alt="Navegación y localización en robótica" class="robot_img medium">
                <br>
                <img src="/src/img/modulos/robot/imagen5.jpg" alt="Navegación y localización en robótica" class="robot_img medium">
            </div>

        </div>
    </div>
    <hr>
    <div class="component-section">
        <div class="content-section">
            <h3>Aplicaciones de la robótica avanzada</h3>
            <p>La robótica avanzada tiene una amplia gama de aplicaciones en diversas industrias, incluyendo:</p>
            <ul>
                <li><strong>Manufactura:</strong> Robots colaborativos (cobots) que trabajan junto a humanos en líneas de producción para ensamblaje, soldadura y embalaje.</li>
                <li><strong>Logística:</strong> Robots autónomos que gestionan inventarios, transportan mercancías y optimizan rutas en almacenes.</li>
                <li><strong>Salud:</strong> Robots quirúrgicos que asisten en procedimientos médicos con alta precisión y robots de rehabilitación para pacientes.</li>
                <li><strong>Agricultura:</strong> Robots que realizan tareas como siembra, riego y cosecha, mejorando la eficiencia agrícola.</li>
                <li><strong>Exploración:</strong> Robots utilizados en exploración espacial, submarina y en entornos peligrosos donde la presencia humana es limitada.</li>
            </ul>
            <p>Estas aplicaciones demuestran cómo la robótica avanzada está transformando industrias enteras, mejorando la productividad, seguridad y calidad de los productos y servicios.</p>
        </div>
    </div>

    

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
