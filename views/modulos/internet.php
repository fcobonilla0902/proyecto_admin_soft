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
  <h2>¿Qué es el Internet de las Cosas (IoT)?</h2>
    <hr>
    <p>El <span class="resaltado">Internet de las Cosas</span> (<span class="resaltado">Internet of Things</span> o <span class="resaltado">IoT</span>) es, esencialmente, una red gigantesca de <span class="resaltado">objetos físicos</span> (las "cosas") que están conectados a internet. Estos objetos están equipados con <span class="resaltado">sensores, software y tecnologías</span> que les permiten <span class="resaltado">recoger, intercambiar y actuar</span> automáticamente según la información que reciben.</p>
    <p>La función principal del IoT es <span class="resaltado">comunicar datos sin intervención humana constante</span>. Esto transforma cualquier objeto cotidiano —desde un reloj inteligente, una cámara de seguridad o un refrigerador, hasta sensores industriales y dispositivos médicos— en una fuente de información y un punto de acción.</p>

    <div class="image-container">
        <img src="/src/img/modulos/internet/imagen1.png" alt="Esquema general del Internet de las Cosas conectando dispositivos, datos y la nube" class="internet_img large">
        <small>El IoT permite que dispositivos, personas y sistemas interactúen entre sí de manera autónoma.</small>
    </div>
    <br>

    <h3>Un Ejemplo Sencillo: El Termostato Inteligente</h3>
    <p>Imagina un <span class="resaltado">termostato inteligente</span> conectado. Este dispositivo detecta la temperatura actual de tu casa y la compara con la temperatura ideal que has programado. Si detecta una desviación, ajusta automáticamente la calefacción o el aire acondicionado para mantenerla confortable. Este proceso ocurre de forma autónoma, e incluso te permite controlarlo desde una app móvil aunque no estés en casa. En este ejemplo, el termostato es la "cosa", y la red Wi-Fi y la aplicación son parte de su infraestructura IoT.</p>

    <hr>

    <h2>Componentes Fundamentales del Sistema IoT</h2>
    <p>Para que un sistema de Internet de las Cosas funcione de manera efectiva, se requieren cuatro elementos clave que trabajan juntos en un ciclo constante:</p>

    <div class="component-section">
        <h3>1. Dispositivos o "Cosas" Inteligentes</h3>
        <div class="component-content-wrapper">
            <div class="component-text">
                <p>Son los <span class="resaltado">objetos físicos</span> equipados con capacidad de recolección de datos y comunicación. Estos incluyen: <span class="resaltado">sensores</span> (que miden luz, temperatura, humedad, presión), <span class="resaltado">actuadores</span> (que ejecutan acciones físicas como abrir una válvula o encender una luz), cámaras, electrodomésticos, relojes, etc. Su misión es interactuar con el mundo físico y digitalizar esa interacción.</p>
                <p>Además de la captura de datos, estos dispositivos tienen la capacidad de <span class="resaltado">procesar información localmente</span> (lo que se conoce como <span class="resaltado">Edge Computing</span>) antes de enviarla a la nube. Esto reduce la latencia y la cantidad de datos transmitidos, siendo crucial para aplicaciones críticas como la robótica industrial o la conducción autónoma.</p>
                <p>La <span class="resaltado">eficiencia energética</span> es un factor clave en su diseño. Muchos dispositivos IoT, especialmente aquellos ubicados en entornos remotos o que operan con batería, utilizan protocolos de comunicación de baja potencia para maximizar su vida útil, como <span class="resaltado">BLE (Bluetooth Low Energy)</span> o <span class="resaltado">LoRaWAN</span>. Esto asegura una monitorización continua y sostenible.</p>
            </div>
            <div class="component-image">
                <img src="/src/img/modulos/internet/imagen3.png" alt="Icono que representa dispositivos conectados, sensores y gadgets" class="internet_img">
            </div>
        </div>
    </div>

    <div class="component-section">
        <h3>2. Conectividad (Redes de Comunicación)</h3>
        <div class="component-content-wrapper">
            <div class="component-text">
                <p>Este es el medio que permite que los dispositivos se <span class="resaltado">comuniquen</span> entre sí y envíen los datos recolectados. Las tecnologías de conectividad son variadas y se eligen según la necesidad de la aplicación (distancia, consumo de energía, volumen de datos):</p>
                <ul>
                    <li><span class="resaltado">Corto alcance:</span> Wi-Fi, Bluetooth.</li>
                    <li><span class="resaltado">Largo alcance:</span> 4G/5G (celular).</li>
                    <li><span class="resaltado">Protocolos especializados de baja potencia:</span> Zigbee o LoRaWAN (ideales para entornos industriales o agrícolas).</li>
                </ul>
            </div>
            <div class="component-image">
                <img src="/src/img/modulos/internet/imagen4.png" alt="Representación de diferentes protocolos de conexión (WiFi, 5G, Bluetooth)" class="internet_img">
            </div>
        </div>
    </div>

    <div class="component-section">
        <h3>3. Plataforma o Nube IoT (Procesamiento y Almacenamiento)</h3>
        <div class="component-content-wrapper">
            <div class="component-text">
                <p>Es la infraestructura central, generalmente basada en la <span class="resaltado">nube</span>, donde los datos enviados por los dispositivos son <span class="resaltado">almacenados, procesados y analizados</span>. Estas plataformas ofrecen herramientas para gestionar los dispositivos, aplicar lógica de negocio y realizar análisis en tiempo real.</p>
                <p>La plataforma es esencial para la <span class="resaltado">toma de decisiones automatizada</span>. Aquí se implementan algoritmos de <span class="resaltado">Inteligencia Artificial (IA)</span> y <span class="resaltado">Machine Learning (ML)</span> que identifican patrones, predicen fallos o activan acciones (como enviar una orden a un actuador) sin requerir supervisión humana directa. La seguridad y la escalabilidad también son manejadas a este nivel.</p>
                <p><em>Ejemplo: Plataformas como AWS IoT Core, Microsoft Azure IoT o Google Cloud IoT permiten analizar vastas cantidades de información para generar perspectivas de valor.</em></p>
            </div>
            <div class="component-image">
                <img src="/src/img/modulos/internet/imagen5.png" alt="Esquema de datos fluyendo hacia una nube de procesamiento central" class="internet_img">
            </div>
        </div>
    </div>

    <div class="component-section">
        <h3>4. Interfaz de Usuario y Aplicaciones</h3>
        <div class="component-content-wrapper">
            <div class="component-text">
                <p>Es el punto donde el <span class="resaltado">usuario interactúa</span> con el sistema. Aunque muchos procesos IoT son autónomos, la interfaz es necesaria para la configuración, la monitorización, y para recibir alertas o notificaciones.</p>
                <p>Esto se presenta habitualmente como una <span class="resaltado">aplicación móvil</span>, un <span class="resaltado">tablero de control</span> (dashboard) en la web, o incluso un <span class="resaltado">altavoz inteligente</span> que permite el control por voz. Estas interfaces no solo muestran datos (como la temperatura actual), sino que permiten al usuario <span class="resaltado">modificar reglas, programar horarios</span> y recibir informes detallados sobre la eficiencia y el uso del sistema.</p>
                <p>Un aspecto crítico de la interfaz es la <span class="resaltado">visualización de datos</span>. Los datos brutos de los sensores deben transformarse en <span class="resaltado">gráficos e indicadores intuitivos</span> que permitan a los usuarios y operadores tomar decisiones rápidas. Las interfaces de IoT a menudo incluyen sistemas de <span class="resaltado">notificación avanzada</span> para alertar sobre eventos críticos (ej. fuga de agua, falla de maquinaria) en tiempo real.</p>
            </div>
            <div class="component-image">
                <img src="/src/img/modulos/internet/imagen6.png" alt="Icono de una pantalla de aplicación móvil mostrando datos o controles" class="internet_img">
            </div>
        </div>
    </div>

<hr>

    <h2>Aplicaciones Clave del IoT en la Sociedad</h2>
    <p>El Internet de las Cosas ha dejado de ser una idea futurista para convertirse en una realidad presente en múltiples aspectos de nuestra vida diaria. El objetivo es crear <span class="resaltado">ecosistemas más eficientes, sostenibles y seguros</span>.</p>

    <div class="component-section">
        <h3>Hogares Inteligentes (Smart Home)</h3>
        <p>Es la aplicación más visible para el consumidor. La conectividad transforma lo cotidiano, mejorando la comodidad, la eficiencia energética y la seguridad. Incluye:</p>
        <ul>
            <li><span class="resaltado">Automatización:</span> Focos que se encienden al detectar movimiento o termostatos que optimizan el consumo de energía.</li>
            <li><span class="resaltado">Seguridad:</span> Cerraduras inteligentes y cámaras que envían alertas ante accesos no autorizados.</li>
            <li><span class="resaltado">Asistencia:</span> Altavoces inteligentes (Alexa, Google Home) que controlan todos los aparatos con comandos de voz.</li>
        </ul>
        <div class="image-container half-width">
            <img src="/src/img/modulos/internet/imagen7.png" alt="Cerradura Inteligente" class="internet_img">
            <img src="/src/img/modulos/internet/imagen8.png" alt="Alexa" class="internet_img">
        </div>
    </div>

    <div class="component-section">
        <h3>Industria 4.0 (IIoT) y Manufactura</h3>
        <p>En la manufactura y la logística, el IoT se conoce como el <span class="resaltado">Internet Industrial de las Cosas (IIoT)</span>. Los sensores se utilizan para monitorear maquinaria en tiempo real, lo que permite:</p>
        <ul>
            <li><span class="resaltado">Mantenimiento Predictivo:</span> Detectar fallos o desgastes antes de que ocurran, evitando costosos tiempos de inactividad.</li>
            <li><span class="resaltado">Optimización de Procesos:</span> Ajustar las líneas de producción en tiempo real basándose en los datos de rendimiento.</li>
            <li><span class="resaltado">Gestión de Inventario:</span> Sensores en almacenes que rastrean la ubicación y cantidad de productos automáticamente.</li>
        </ul>
        <div class="image-container half-width">
            <img src="/src/img/modulos/internet/imagen9.png" alt="Optimización de Procesos" class="internet_img">
            <img src="/src/img/modulos/internet/imagen10.png" alt="Gestión de Inventario" class="internet_img">
        </div>
    </div>

    <div class="component-section">
        <h3>Salud Conectada (eHealth) y Wearables</h3>
        <div class="component-content-wrapper">
            <div class="component-text">
                <p>Los dispositivos médicos conectados permiten un <span class="resaltado">seguimiento continuo</span> de signos vitales (frecuencia cardíaca, nivel de glucosa, presión arterial, etc.). Esto mejora la atención médica y la respuesta ante emergencias, ya que pueden enviar <span class="resaltado">alertas automáticas</span> al personal médico si se detectan anomalías. Los <span class="resaltado">wearables</span> o dispositivos ponibles son el ejemplo más común de esta aplicación.</p>
                <p>El IoT en la salud no solo se limita al monitoreo, sino que también facilita la <span class="resaltado">telemedicina</span> y la <span class="resaltado">gestión de activos hospitalarios</span>. Permite la monitorización remota de pacientes crónicos en sus hogares, reduciendo las visitas al hospital y mejorando su calidad de vida, un concepto conocido como <span class="resaltado">Cuidado Asistido Remoto (RAC)</span>.</p>
            </div>
            <div class="component-image">
                <div class="image-container half-width">
                    <img src="/src/img/modulos/internet/imagen11.png" alt="eHealth" class="internet_img">
                </div>
            </div>
        </div>
    </div>

    <div class="component-section">
        <h3>Ciudades Inteligentes (Smart Cities)</h3>
        <div class="component-content-wrapper">
            <div class="component-text">
                <p>El IoT facilita la <span class="resaltado">gestión de servicios públicos</span> a gran escala, transformando las ciudades en entornos más eficientes y habitables. Las ciudades usan sensores para:</p>
                <ul>
                    <li><span class="resaltado">Tráfico Inteligente:</span> Sistemas automatizados que ajustan semáforos en tiempo real según la densidad vehicular, reduciendo la congestión y los tiempos de viaje.</li>
                    <li><span class="resaltado">Servicios Públicos Eficientes:</span> Alumbrado público que se ajusta según la luz ambiental y la presencia de personas para <span class="resaltado">ahorrar energía</span>.</li>
                    <li><span class="resaltado">Gestión de Residuos:</span> Sensores de llenado en contenedores que optimizan las rutas de recolección de basura, ahorrando combustible y costes operativos.</li>
                    <li><span class="resaltado">Calidad Ambiental:</span> Estaciones con sensores que miden la calidad del aire y el nivel de ruido, proporcionando datos cruciales para la planificación urbana y la salud pública.</li>
                </ul>
            </div>
            <div class="component-image">
                <div class="image-container half-width">
                    <img src="/src/img/modulos/internet/imagen12.png" alt="Smart Cities" class="internet_img">
                </div>
            </div>
        </div>
    </div>

    <br><br>

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