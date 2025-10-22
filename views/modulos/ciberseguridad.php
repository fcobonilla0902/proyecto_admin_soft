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
    $modulo = 5;       

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
$modulo = 5; 
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

$titulo = "Módulo 5"; 
include_once '../../templates/header.php';  
?> 


<link rel="stylesheet" href="/src/css/modulos/ciber.css">

<main class="main-container" id="main-content">    
    <div id="block">        
        <a href="/views/industria.php?op=2" class="back-arrow-link">            
            <img src="/src/img/flecha_back.png" alt="Volver a Industria 4.0">        
        </a>    
    </div>    

    <div id="progress-header">        
        <h2><?php echo $titulo . " - Ciberseguridad";?></h2> 
        <div id="progress-info">            
            <div id="scroll-progress-container">                
                <div id="progress-bar" style="width: <?php echo $porcentaje_inicial; ?>%;"></div>            
            </div>            
            <div id="percentage-display"><?php echo $porcentaje_inicial; ?>%</div>        
        </div>    
    </div>    

    <h2>La ciberseguridad en la industria</h2>
    <div class="content-section">
        <p>En la actualidad, la ciberseguridad es necesaria en las empresas, donde las tecnologías de la información y la comunicación (TIC) están cada vez más integradas en los procesos industriales. La ciberseguridad industrial se refiere a la protección de los sistemas, redes y datos utilizados en entornos industriales contra amenazas cibernéticas, como ataques informáticos, malware, robo de datos y sabotaje.</p>

        <p>En el contexto de la industria, la ciberseguridad es esencial para garantizar la continuidad operativa, la seguridad de los trabajadores y la protección de los activos críticos. Los sistemas industriales, como los sistemas de control industrial (ICS) y las redes de automatización, son vulnerables a ataques cibernéticos que pueden causar interrupciones en la producción, daños a los equipos y riesgos para la seguridad física.</p>

    </div>

     <div class="image-container">
        <img src="/src/img/modulos/ciber/imagen1.png" alt="Ciberseguridad en las empresas" class="ciber_img large"> <br>
        <small>Dentro del contexto empresarial, la ciberseguridad busca cubrir tanto los servicios como capacitación de los empleados.</small>
    </div>
    <hr>
    <h2>Conceptos clave</h2>
    <div class="component-section">
        <h3>¿Qué es la ciberseguridad?</h3>
        <div class="component-content-wrapper">
            <div class="content-section">
                <p>La ciberseguridad se refiere a la práctica de proteger los sistemas, redes y datos digitales contra amenazas cibernéticas, como ataques informáticos, malware, robo de datos y sabotaje. En el contexto de la industria, la ciberseguridad es esencial para garantizar la continuidad operativa, la seguridad de los trabajadores y la protección de los activos críticos.</p>

                <p>Los sistemas industriales, como los sistemas de control industrial (ICS) y las redes de automatización, son vulnerables a ataques cibernéticos que pueden causar interrupciones en la producción, daños a los equipos y riesgos para la seguridad física. La ciberseguridad industrial implica la implementación de medidas de seguridad específicas para proteger estos sistemas y garantizar su integridad y disponibilidad.</p>
            </div>
            <div class="image-container-width">
                <img src="/src/img/modulos/ciber/imagen2.png" alt="Ciberseguridad industrial" class="ciber_img medium">
            </div>
        </div>
    </div>

    <div class="component-section">
        <h3>La CIA (los tres pilares de la ciberseguridad)</h3>
        <div class="component-content-wrapper">
            <div class="content-section">
                <p>La CIA es un modelo fundamental en la ciberseguridad que se basa en tres pilares esenciales: Confidencialidad, Integridad y Disponibilidad. Estos principios son cruciales para proteger la información y los sistemas en entornos industriales.</p>

                <ul>
                    <li><strong>Confidencialidad:</strong> Garantiza que la información sensible solo sea accesible para personas autorizadas. En la industria, esto implica proteger datos críticos, como secretos comerciales, información de clientes y datos operativos, mediante el uso de cifrado, controles de acceso y políticas de seguridad.</li>
                    <li><strong>Integridad:</strong> Asegura que la información y los sistemas no sean alterados o manipulados de manera no autorizada. En entornos industriales, esto es vital para mantener la precisión de los datos operativos y evitar modificaciones maliciosas que puedan afectar la producción o la seguridad.</li>
                    <li><strong>Disponibilidad:</strong> Garantiza que los sistemas y la información estén accesibles cuando se necesiten. En la industria, esto implica implementar medidas para prevenir interrupciones en los sistemas críticos, como ataques DDoS o fallos técnicos, asegurando así la continuidad operativa.</li>
                </ul>
            </div>
            <div class="image-container-width">
                <img src="/src/img/modulos/ciber/imagen3.png" alt="Los tres pilares de la ciberseguridad" class="ciber_img medium">
            </div>
        </div>
    </div>
    <hr>
    <h2>Amenazas y mecanismos de defensa</h2>

    <div class="component-section">
        <h3>Principales amenazas</h3>
        <div class="component-content-wrapper">
            <div class="content-section">
                <p>Las principales amenazas en ciberseguridad industrial incluyen:</p>
                <ul>
                    <li><strong>Malware:</strong> Software malicioso diseñado para infiltrarse y dañar sistemas industriales, como ransomware o troyanos.</li>
                    <li><strong>Phishing:</strong> Técnicas de ingeniería social para engañar a los empleados y obtener acceso no autorizado a sistemas críticos.</li>
                    <li><strong>Ataques DDoS:</strong> Ataques que buscan saturar los sistemas industriales, causando interrupciones en la producción.</li>
                    <li><strong>Exploits de vulnerabilidades:</strong> Aprovechamiento de fallos de seguridad en software o hardware industrial para obtener acceso no autorizado.</li>
                </ul>
            </div>

            <div class="image-container-width">
                <img src="/src/img/modulos/ciber/imagen4.jpg" alt="Principales amenazas en ciberseguridad" class="ciber_img medium">
            </div>
        </div>
    </div>

    <div class="component-section">
        <h3>La ingeniería social</h3>
        <div class="component-content-wrapper">
            <div class="content-section">
                <p>La ingeniería social es una técnica utilizada por los ciberdelincuentes para manipular a las personas y obtener acceso no autorizado a sistemas o información. En el contexto de la ciberseguridad industrial, la ingeniería social puede ser una amenaza significativa, ya que los empleados pueden ser engañados para revelar credenciales de acceso, descargar malware o realizar acciones que comprometan la seguridad de los sistemas industriales.</p>

                <p>Para protegerse contra la ingeniería social, es fundamental implementar programas de concienciación y capacitación para los empleados, enseñándoles a reconocer intentos de phishing y otras tácticas de manipulación. Además, se deben establecer políticas de seguridad claras y procedimientos para verificar la identidad antes de divulgar información sensible o realizar cambios en los sistemas.</p>
            </div>
            <div class="image-container-width">
                <img src="/src/img/modulos/ciber/imagen5.jpg" alt="Ingeniería social" class="ciber_img medium">
            </div>
        </div>
    </div>

    <div class="component-section">
        <h3>Mecanismos de defensa esenciales</h3>
        <div class="component-content-wrapper">
            <div class="content-section">
                <p>Los mecanismos de defensa esenciales en ciberseguridad industrial incluyen:</p>
                <ul>
                    <li><strong>Firewalls y sistemas de detección de intrusiones (IDS):</strong> Para monitorear y controlar el tráfico de red, identificando actividades sospechosas.</li>
                    <li><strong>Cifrado:</strong> Para proteger la confidencialidad de los datos sensibles durante la transmisión y el almacenamiento.</li>
                    <li><strong>Control de acceso:</strong> Implementación de políticas estrictas para garantizar que solo el personal autorizado tenga acceso a sistemas críticos.</li>
                    <li><strong>Actualizaciones y parches regulares:</strong> Mantener el software y hardware industrial actualizados para corregir vulnerabilidades conocidas.</li>
                    <li><strong>Copias de seguridad:</strong> Realizar copias de seguridad periódicas de datos críticos para garantizar la recuperación en caso de un ataque.</li>
                </ul>
            </div>
            <div class="image-container-width">
                <img src="/src/img/modulos/ciber/imagen6.jpg" alt="Mecanismos de defensa en ciberseguridad" class="ciber_img medium">
            </div>
        </div>
    </div>

    <div class="component-section">
        <h3>Criptografía</h3>
        <div>
            <div class="content-section">
                <p>La criptografía es una disciplina fundamental en la ciberseguridad que se encarga de proteger la información mediante técnicas de cifrado y descifrado. En el contexto de la ciberseguridad industrial, la criptografía juega un papel crucial para garantizar la confidencialidad, integridad y autenticidad de los datos transmitidos y almacenados en sistemas industriales.</p>

                <p>Existen varios métodos criptográficos utilizados en la ciberseguridad industrial, entre ellos:</p>
                <ul>
                    <li><strong>Cifrado simétrico:</strong> Utiliza la misma clave para cifrar y descifrar la información. Es eficiente para proteger grandes volúmenes de datos, pero requiere un manejo seguro de las claves.</li>
                    <li><strong>Cifrado asimétrico:</strong> Utiliza un par de claves (pública y privada) para cifrar y descifrar la información. Es útil para intercambiar claves de manera segura y autenticar usuarios.</li>
                    <li><strong>Funciones hash:</strong> Generan un valor único a partir de los datos originales, utilizado para verificar la integridad de la información.</li>
                </ul>

                <p>La implementación adecuada de técnicas criptográficas en sistemas industriales es esencial para proteger la información crítica contra accesos no autorizados, manipulaciones y ataques cibernéticos.</p>
            </div>            <div class="image-container-width">
                <img src="/src/img/modulos/ciber/imagen7.jpg" alt="Criptografía en ciberseguridad" class="ciber_img medium">
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
