<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../../includes/database.php'; 
    include '../../includes/funciones.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    isAuth();

    //Obtenemos datos del JS
    $data = json_decode(file_get_contents('php://input'), true);
    $porcentaje_nuevo = isset($data['porcentaje']) ? (int)$data['porcentaje'] : 0; 

    $identificador = $_SESSION['alumno']['identificador'] ?? null;
    $modulo = 1; 

    // Solo procede si tenemos un identificador
    if ($identificador) {
        
        // ======================================================
        // 1. CONSULTA: VERIFICAR SI EL REGISTRO YA EXISTE
        // ======================================================
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
            // El registro EXISTE. Solo actualizamos si el nuevo porcentaje es MAYOR.
            $porcentaje_actual_db = (int)$registro_existente['porcentaje'];
            $porcentaje_a_guardar = max($porcentaje_actual_db, $porcentaje_nuevo);
            
            // Si el nuevo valor es igual al actual, no hacemos nada para evitar una consulta UPDATE innecesaria.
            if ($porcentaje_a_guardar > $porcentaje_actual_db) {
                // ===============================================
                // 2. ACCIÓN: UPDATE (Si el porcentaje es mayor)
                // ===============================================
                $query = "UPDATE records_modulos SET porcentaje = ? WHERE identificador = ? AND modulo = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("iss", $porcentaje_a_guardar, $identificador, $modulo);
                $exito = $stmt->execute();
                $accion = 'actualizado';
                $stmt->close();
            } else {
                $exito = true; // Ya está guardado con un porcentaje igual o mayor
                $accion = 'sin_cambios';
            }
            
        } else {
            // El registro NO EXISTE. Lo insertamos.
            // ===============================================
            // 2. ACCIÓN: INSERT
            // ===============================================
            $query = "INSERT INTO records_modulos (identificador, modulo, porcentaje) VALUES (?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssi", $identificador, $modulo, $porcentaje_nuevo);
            $exito = $stmt->execute();
            $accion = 'insertado';
            $stmt->close();
        }

        // Mandamos el JSON de respuesta y terminamos la ejecución.
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
    
    exit; // Termina la ejecución para no mostrar el HTML
}

// =================================================================
// LÓGICA PHP (Ejecutada en solicitud GET/Carga inicial de la página)
// =================================================================

include '../../includes/database.php'; 
include '../../includes/funciones.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
isAuth();

// Obtener el porcentaje actual para inicializar JS
$identificador = $_SESSION['alumno']['identificador'] ?? '';
$modulo = 1;
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


$titulo = "Módulo 1";
include_once '../../templates/header.php'; 
?>
<link rel="stylesheet" href="/src/css/modulos/internet.css">

<main class="main-container" id="main-content"> 
    <div id="block">
        <a href="/views/industria.php?op=2" class="back-arrow-link">
            <img src="/src/img/flecha_back.png" alt="Volver a Industria 4.0">
        </a>
    </div>
    <div id="progress-header">
        
        <h2><?php echo $titulo . " - Internet de las Cosas (IoT)";?></h2> <div id="progress-info">
            <div id="scroll-progress-container">
                <div id="progress-bar" style="width: <?php echo $porcentaje_inicial; ?>%;">
                    </div>
            </div>
            
            <div id="percentage-display">
                <?php echo $porcentaje_inicial; ?>%
            </div>
        </div>
    </div>
    
    <h3>¿Qué es IoT?</h3>
    <p>El Internet de las Cosas (Internet of Things) es una red de objetos físicos conectados a internet que pueden recoger, intercambiar y actuar según la información que reciben. 
        Estos objetos pueden ser desde relojes inteligentes,cámaras de seguridad, refrigeradores o automóviles, hasta sensores industriales y dispositivos médicos.</p>
    <br>

    <img src="" alt="Imagen 1">

    <br><br>

    <p>Su función principal es comunicar datos sin intervención humana constante. </p>
    <br>
    <img src="" alt="Imagen 2">

    <br><br>

    <p>Por ejemplo, imagina un termostato inteligente que detecta la temperatura de tu casa y ajusta automáticamente la calefacción o el aire acondicionado para mantenerla confortable, 
        incluso cuando no estás en casa. Componentes principales Dispositivos o “cosas” inteligentes</p>
    <br>
    <img src="" alt="Imagen 3">

    <br><br>

    <p>Son los objetos físicos conectados: sensores, cámaras, electrodomésticos, relojes, etc.</p>
    <br>

    <p>Conectividad</p>
    <img src="" alt="Imagen 4">

    <br><br>

    <p>Permite que los dispositivos se comuniquen entre sí. Puede ser Wi-Fi, Bluetooth, 4G/5G o protocolos especializados como Zigbee o LoRaWAN. Plataforma o nube IoT</p>
    <br>
    <img src="" alt="Imagen 5">

    <br><br>

    <p>Es donde se almacenan y procesan los datos enviados por los dispositivos.</p>
    <p>Ejemplo: plataformas como AWS IoT Core o Google Cloud IoT permiten analizar información en tiempo real.</p>

    <br><br>

    <p>Interfaz de usuario</p>
    <img src="" alt="Imagen 6">
    <br>
    <p>Es cómo el usuario interactúa con el sistema, por ejemplo, mediante una app que muestra la temperatura o notificaciones de seguridad.</p>
    <br><br><br>

    <h3>Aplicaciones del IoT</h3>
    <p>El Internet de las Cosas ha dejado de ser una idea futurista para convertirse en una realidad presente en múltiples aspectos de nuestra vida diaria. En el ámbito 
        doméstico, los hogares inteligentes son uno de los ejemplos más claros de cómo la conectividad transforma lo cotidiano. Dispositivos como focos que se encienden 
        automáticamente al detectar movimiento, refrigeradores que avisan cuando falta alimento, o asistentes de voz como Alexa y Google Home que controlan otros aparatos 
        con simples comandos, muestran cómo la automatización puede mejorar la comodidad, la eficiencia energética y la seguridad. De igual forma, las cerraduras inteligentes 
        permiten abrir o cerrar puertas desde una aplicación móvil, e incluso enviar alertas cuando alguien intenta acceder sin permiso, uniendo funcionalidad con protección.
    </p>
    <p>    
        Fuera del hogar, el IoT también tiene un impacto profundo en sectores más amplios como la industria, la salud, el transporte y las ciudades. En la Industria 4.0, los
        sensores IoT se utilizan para monitorear maquinaria en tiempo real, detectar fallos antes de que ocurran y optimizar los procesos de producción, reduciendo costos y
        tiempos de inactividad. En el sector salud, los dispositivos médicos conectados permiten un seguimiento continuo de signos vitales, como la frecuencia cardíaca o el 
        nivel de glucosa, y pueden enviar alertas automáticas al personal médico si se detectan anomalías, mejorando la atención y la respuesta ante emergencias. Por otro lado,
        los vehículos conectados son capaces de recibir actualizaciones de software, analizar su propio estado mecánico o compartir información sobre el tráfico para evitar 
        accidentes. Finalmente, en las llamadas ciudades inteligentes, el IoT facilita la gestión de servicios públicos, como el alumbrado que se ajusta según la luz ambiental, 
        la recolección de basura optimizada mediante sensores de llenado o los sistemas de tráfico automatizados que reducen la congestión. En conjunto, todas estas aplicaciones
        muestran que el IoT no solo busca conectar objetos, sino crear ecosistemas más eficientes, sostenibles y seguros para la sociedad.</p>
</main>

<script>
    // Inicializamos maxPercentage con el valor de la base de datos al cargar la página
    let maxPercentage = <?php echo $porcentaje_inicial; ?>; 

    // 1. Nueva función para calcular la posición de scroll
    function scrollToPercentage(percentage) {
        const mainElement = document.getElementById('main-content');
        if (!mainElement) return;

        // Si el porcentaje es 0 o 100, no hacemos scroll, o vamos al final directamente
        if (percentage === 0) return;
        
        // La altura total que se puede scrollear
        const scrollHeight = mainElement.scrollHeight;
        const clientHeight = mainElement.clientHeight;
        const totalScrollableHeight = scrollHeight - clientHeight;
        
        if (totalScrollableHeight > 0) {
            // Calcula la posición de scroll necesaria (en píxeles)
            // Multiplicamos el porcentaje (convertido a decimal) por la altura total a scrollear
            const targetScrollTop = (percentage / 100) * totalScrollableHeight;
            
            // Usamos scrollIntoView o scrollTop. scrollTop es más directo para un contenedor.
            // Usamos 'behavior: smooth' si queremos un efecto suave (opcional, puedes usar 'auto').
            mainElement.scrollTo({
                top: targetScrollTop,
                behavior: 'auto' // 'auto' para un salto inmediato al cargar
            });
        }
    }


    // Actualizamos la vista inmediatamente con el valor inicial
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

        // Si el usuario llegó al final, forzamos 100%
        if (scrollTop + clientHeight >= scrollHeight - 2) {
            percentage = 100;
        }

        percentage = Math.min(100, Math.max(0, percentage));

        // Redondeamos el porcentaje actual para la comparación y visualización
        const currentRoundedPercentage = Math.round(percentage);

        //Solo guardamos si el nuevo valor redondeado es mayor que el máximo guardado
        if (currentRoundedPercentage > maxPercentage) {
            maxPercentage = currentRoundedPercentage;
            saveProgress(maxPercentage); // Guardamos el nuevo máximo
        }

        // Actualizamos siempre la visualización con el maxPercentage alcanzado
        document.getElementById('percentage-display').textContent = maxPercentage + '%';
        document.getElementById('progress-bar').style.width = maxPercentage + '%';
    }

    // Enviamos el progreso al servidor
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
    
    // 2. Ejecutar la función para ir al porcentaje guardado DESPUÉS de cargar la página
    window.addEventListener('load', () => {
        // Primero actualiza la vista con el porcentaje inicial
        updateScrollPercentage(); 
        // Luego, desplázate a esa posición
        scrollToPercentage(maxPercentage); 
    });
    
    // Eliminamos el listener 'resize' o lo modificamos si es necesario, pero no tiene impacto directo en este requerimiento
    // window.addEventListener('resize', updateScrollPercentage); 
</script>


<?php include_once '../../templates/footer.php'; ?>