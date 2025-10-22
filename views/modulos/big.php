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
    $modulo = 3;       

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
$modulo = 3; 
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

$titulo = "Módulo 3"; 
include_once '../../templates/header.php';  
?> 

<link rel="stylesheet" href="/src/css/modulos/big.css">

<main class="main-container" id="main-content">    
    <div id="block">        
        <a href="/views/industria.php?op=2" class="back-arrow-link">            
            <img src="/src/img/flecha_back.png" alt="Volver a Industria 4.0">        
        </a>    
    </div>    

    <div id="progress-header">        
        <h2><?php echo $titulo . " - Big Data";?></h2> 
        <div id="progress-info">            
            <div id="scroll-progress-container">                
                <div id="progress-bar" style="width: <?php echo $porcentaje_inicial; ?>%;"></div>            
            </div>            
            <div id="percentage-display"><?php echo $porcentaje_inicial; ?>%</div>        
        </div>    
    </div>    

    <h2>¿Qué es el Big Data?</h2>
    <hr>

    <p>
        El Big Data se refiere a los conjuntos de datos de un tamaño y complejidad tan masivos que las herramientas tradicionales de procesamiento y análisis de datos no son capaces de capturarlos, gestionarlos ni procesarlos en un tiempo razonable. 
        No se trata solo de "muchos datos", sino de la incapacidad de las arquitecturas convencionales para manejarlos.
    </p>

    <p>
        En la era digital actual, generamos datos a una velocidad sin precedentes. Cada clic, cada transacción, cada video visto y cada sensor conectado a Internet contribuye a un océano de información. 
        El verdadero poder del Big Data no reside en la cantidad, sino en la capacidad de analizar estos volúmenes para descubrir patrones ocultos, tendencias de mercado, correlaciones inesperadas y conocimientos (insights) que permiten tomar decisiones más inteligentes y estratégicas
    </p>

    <div class="image-container">
        <img src="/src/img/modulos/big/imagen1.jpg" alt="Representación de una mente digital conectada a una red de datos" class="ia_img large"> <br>
        <small>El Big Data es el "nuevo petróleo": un recurso masivo que, una vez refinado mediante el análisis, genera un inmenso valor.</small>
    </div>
    <hr>

    <h2>Las 5 "V" del Big Data</h2>

    <div class="component-section">
        <div class="component-content-wrapper">
            <div class="component-text">
            <h3>1. Volumen</h3>
            <p>El volumen se refiere a la gran cantidad de datos generados. Los big data se caracterizan por su tamaño, que puede ser mucho mayor que el de los datos tradicionales. 
                La gestión y el análisis de grandes volúmenes de datos requieren infraestructuras y herramientas especiales.</p>
            <p>En estos casos de grandes cantidades de datos, hablamos de Terabytes (TB), Petabytes (PB) e incluso Exabytes (EB) de información.</p>
            <span class="resaltado">Ejemplos:
                <ul>
                    <li>Los datos de transacciones de todas las tarjetas de crédito del mundo en un día.</li>
                    <li>Los datos generados por los sensores de un avión comercial en un solo vuelo.</li>
                </ul> 
            </span>
            </div>
            <div class="component-image">
                <div class="image-container-width">
                    <img src="/src/img/modulos/big/imagen2.png" alt="Representación visual del volumen de datos" class="big_img">
                </div>
            </div>
        </div>
    </div>

    <div class="component-section">
        <div class="component-content-wrapper">
            <div class="component-text">
                <h3>2. Varianza</h3>
                <p>La varianza se refiere a la diversidad de tipos de datos en el contexto de los macrodatos. Los datos pueden proceder de distintas fuentes, como las redes sociales, los sensores, los dispositivos móviles o las bases de datos corporativas, y pueden ser: estructurados, no estructurados o semiestructurados. 
                    <span class="resaltado">Gestionar y analizar esta variedad de datos requiere soluciones flexibles y adaptables.</span></p>
                <ul>
                    <li><span class="resaltado">Datos estructurados:</span> Datos organizados en tablas y bases de datos relacionales, como hojas de cálculo.</li>
                    <li><span class="resaltado">Datos no estructurados:</span> Datos sin una estructura predefinida, como correos electrónicos, videos, imágenes y publicaciones en redes sociales.</li>
                    <li><span class="resaltado">Datos semiestructurados:</span> Datos que no encajan perfectamente en tablas, pero que tienen cierta organización, como archivos XML o JSON.</li>
                </ul>
            </div>

            <div class="component-image">
                <div class="image-container-width">
                    <img src="/src/img/modulos/big/imagen3.png" alt="Representación visual de la variedad de datos" class="big_img">
                </div>
            </div>
        </div>
    </div>

    <div class="component-section">
        <div class="component-content-wrapper">
            <div class="component-text">
                <h3>3. Velocidad</h3>
                <p>La velocidad se refiere a la rapidez con la que se generan y recopilan los datos. En el contexto de los macrodatos, los datos pueden generarse en tiempo real o con gran frecuencia. Por lo tanto, la capacidad de procesar y analizar los datos en tiempo real resulta crucial. 
                    El objetivo es obtener información útil y capacidad de respuesta en las decisiones empresariales.</p>
                <span class="resaltado">Ejemplos:</span>
                <ul>
                    <li>El flujo constante de publicaciones en redes sociales</li>
                    <li>Los datos generados por sensores en dispositivos IoT (Internet de las cosas)</li>
                    <li>Las transacciones financieras en línea</li>
                </ul>
            </div>

            <div class="component-image">
                <div class="image-container-width">
                    <img src="/src/img/modulos/big/imagen4.png" alt="Representación visual de la velocidad de generación de datos" class="big_img">
                </div>
            </div>
        </div>
    </div>

    <div class="component-section">
        <div class="component-content-wrapper">
            <div class="component-text">
                <h3>4. Veracidad</h3>
                <p>La veracidad se refiere a la calidad y fiabilidad de los datos. En el contexto de los macrodatos, es importante garantizar que los datos recopilados sean precisos, completos y no contengan errores. 
                    Deben aplicarse procesos de control de calidad para garantizar que los datos son fiables y que la información obtenida es válida.</p>
                <p>Asegurar la veracidad de los datos es crucial porque:</p>
                <ul>
                    <li><span class="resaltado">Decisiones informadas:</span> Las decisiones basadas en datos inexactos pueden llevar a resultados erróneos y perjudiciales.</li>
                    <li><span class="resaltado">Confianza:</span> La confianza en los datos es esencial para que las partes interesadas acepten y utilicen los análisis de big data.</li>
                    <li><span class="resaltado">Eficiencia operativa:</span> Los datos de alta calidad mejoran la eficiencia de los procesos empresariales y reducen costos asociados a errores.</li>
            </div>

            <div class="component-image">
                <div class="image-container-width">
                    <img src="/src/img/modulos/big/imagen5.png" alt="xd" class="big_img">
                </div>
            </div>
        </div>
    </div>

    <div class="component-section">
        <div class="component-content-wrapper">
            <div class="component-text">
                <h3>5. Valor</h3>
                <p> El valor representa el valor potencial que puede derivarse de los datos. Los macrodatos ofrecen la oportunidad de analizar y explotar los datos para obtener información valiosa, identificar tendencias, patrones y correlaciones, mejorar la toma de decisiones, identificar nuevas oportunidades de negocio y ofrecer una experiencia personalizada al cliente.</p>
            </div>

            <div class="component-image">
                <div class="image-container-width">
                    <img src="/src/img/modulos/big/imagen6.png" alt="Representación visual del valor de los datos" class="big_img">
                </div>
            </div>
        </div>
    </div>
    <hr>

    <h2>Tecnologías Clave del Ecosistema Big Data</h2>
    <div class="component-section">
        <div class="component-text">
            <h3>1. Apache Hadoop</h3>
            <p>Es el pionero del Big Data. Es un marco de código abierto que permite el procesamiento distribuido de grandes conjuntos de datos a través de clústeres de computadoras.</p>
            <p class="resaltado">Sus dos componentes principales son:</p>
            <ul>
                <li><span class="resaltado">HDFS (Hadoop Distributed File System):</span> Un sistema de archivos distribuido que almacena datos en múltiples nodos.</li>
                <li><span class="resaltado">MapReduce:</span> Un modelo de programación que divide las tareas de procesamiento en partes más pequeñas y las distribuye a través del clúster.</li>
            </ul> 
        </div>
    </div>

    <div class="component-section">
        <div class="component-text">
            <h3>2. Apache Spark</h3>
            <p>Es una plataforma de procesamiento de datos en memoria que ofrece una velocidad y rendimiento superiores en comparación con Hadoop MapReduce. Spark es ideal para tareas de análisis en tiempo real, aprendizaje automático y procesamiento de gráficos.</p>
            <p class="resaltado">Sus características clave incluyen:</p>
            <ul>
                <li><span class="resaltado">Procesamiento en memoria:</span> Almacena datos en la memoria RAM para un acceso rápido.</li>
                <li><span class="resaltado">APIs versátiles:</span> Ofrece APIs en Java, Scala, Python y R.</li>
                <li><span class="resaltado">Bibliotecas integradas:</span> Incluye bibliotecas para SQL (Spark SQL), aprendizaje automático (MLlib) y procesamiento de gráficos (GraphX).</li>
            </ul>
        </div>
    </div>

    <div class="component-section">
        <div class="component-text">
            <h3>3. Bases de Datos NoSQL</h3>
            <p>Las bases de datos NoSQL están diseñadas para manejar grandes volúmenes de datos no estructurados y semi-estructurados. A diferencia de las bases de datos relacionales tradicionales, NoSQL ofrece flexibilidad en el esquema y escalabilidad horizontal.</p>
            <p class="resaltado">Tipos comunes de bases de datos NoSQL incluyen:</p>
            <ul>
                <li><span class="resaltado">Documentales:</span> Como MongoDB y CouchDB, que almacenan datos en documentos JSON o BSON.</li>
                <li><span class="resaltado">Clave-Valor:</span> Como Redis y DynamoDB, que almacenan datos como pares clave-valor.</li>
                <li><span class="resaltado">Columnares:</span> Como Apache Cassandra y HBase, que almacenan datos en tablas con columnas flexibles.</li>
                <li><span class="resaltado">Grafos:</span> Como Neo4j, que están optimizadas para almacenar y consultar datos de grafos.</li>   
            </ul>
        </div>

    </div>

    <img src="/src/img/modulos/big/imagen7.png" alt="Herramientas y tecnologías clave del ecosistema Big Data" class="big_img" style="align-self: center; margin-top: 20px;">
    <br><hr>

    <h2>Aplicaciones del Big Data</h2>

    <p>El Big Data tiene aplicaciones en una amplia variedad de industrias y sectores. Algunas de las aplicaciones más comunes incluyen:</p>
    <ul>
        <li><span class="resaltado">Salud:</span> Análisis de datos médicos para mejorar el diagnóstico y tratamiento de enfermedades.</li> <br>
        <li><span class="resaltado">Finanzas:</span> Detección de fraudes, análisis de riesgos y personalización de servicios financieros.</li> <br>
        <li><span class="resaltado">Marketing:</span> Análisis del comportamiento del consumidor para campañas publicitarias dirigidas.</li> <br>
        <li><span class="resaltado">Transporte:</span> Optimización de rutas y gestión del tráfico en tiempo real.</li> <br>
        <li><span class="resaltado">Manufactura:</span> Mantenimiento predictivo y optimización de la cadena de suministro.</li> <br>
    </ul> 
    <p>Estas son solo algunas de las muchas aplicaciones del Big Data. A medida que la tecnología continúa avanzando, se espera que el Big Data desempeñe un papel cada vez más importante en la toma de decisiones y la innovación en diversos campos.</p>
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
