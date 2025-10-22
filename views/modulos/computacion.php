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
    $modulo = 4;       

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
$modulo = 4; 
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

$titulo = "Módulo 4"; 
include_once '../../templates/header.php';  
?> 


<link rel="stylesheet" href="/src/css/modulos/cc.css">

<main class="main-container" id="main-content">    
    <div id="block">        
        <a href="/views/industria.php?op=2" class="back-arrow-link">            
            <img src="/src/img/flecha_back.png" alt="Volver a Industria 4.0">        
        </a>    
    </div>    

    <div id="progress-header">        
        <h2><?php echo $titulo . " - Computación en la Nube";?></h2> 
        <div id="progress-info">            
            <div id="scroll-progress-container">                
                <div id="progress-bar" style="width: <?php echo $porcentaje_inicial; ?>%;"></div>            
            </div>            
            <div id="percentage-display"><?php echo $porcentaje_inicial; ?>%</div>        
        </div>    
    </div>    

    <h2>Computación en la nube</h2>
    <p>La computación en la nube es un modelo de prestación de servicios informáticos a través de Internet. Permite a los usuarios acceder a recursos y servicios informáticos, como almacenamiento, procesamiento y aplicaciones, sin necesidad de tener una infraestructura física propia. En lugar de depender de servidores locales o dispositivos individuales, los usuarios pueden utilizar recursos compartidos alojados en centros de datos remotos.</p>
    <hr> <br>
     <h2>Modelos de Cloud Computing</h2>
    <div class="component-section">
            <div class="component-text">
                <p>Existen diferentes modelos de computación en la nube, como la nube pública, privada e híbrida.</p>
                <ol>
                    <li><strong>Nube Pública:</strong> Es administrada por proveedores externos y está disponible para el público en general. Los usuarios pueden acceder a recursos y servicios compartidos a través de Internet.</li> <br>
                    <li><strong>Nube Privada:</strong> Es utilizada exclusivamente por una organización. Los recursos y servicios están dedicados a esa organización y pueden estar alojados en sus propias instalaciones o en un centro de datos externo.</li> <br>
                    <li><strong>Nube Híbrida:</strong> Combina elementos de ambos modelos, permitiendo a las organizaciones aprovechar las ventajas de ambos enfoques. Pueden utilizar la nube pública para ciertas aplicaciones y la nube privada para datos sensibles o críticos.</li> <br>
                </ol>
            </div>
            <div class="image-container">
                <img src="/src/img/modulos/cc/imagen1.png" alt="Representación visual de la computación en la nube" style="max-width: 70%"">
            </div>
    </div>
    <hr> <br>
    <h2>Servicios de Computación en la nube</h2>
    <div class="component-section">
        <div class="component-text">
            <h3>1. Infraestructura como servicio (IaaS)</h3>
            <p>El proveedor de la nube gestiona la infraestructura física (servidores, redes, virtualización, almacenamiento), y el usuario final es responsable de instalar y gestionar el sistema operativo, el middleware, el runtime y las aplicaciones.</p> <br>

            <h3>2. Plataforma como servicio (PaaS)</h3>
            <p>El proveedor de la nube gestiona la infraestructura subyacente, el sistema operativo y el middleware. Los usuarios se centran únicamente en el desarrollo, despliegue y gestión de sus aplicaciones, sin preocuparse por el entorno operativo.</p> <br>

            <h3>3. Software como servicio (SaaS)</h3>
            <p>Es el modelo más completo y gestionado. El proveedor de la nube gestiona todas las capas de la aplicación, incluyendo la infraestructura, la plataforma y el software. Los usuarios simplemente acceden a la aplicación a través de un navegador web o una API.</p> <br>

            <h3>4. Base de Datos como servicio (DBaaS)</h3>
            <p>Son un modelo que permite a los desarrolladores y empresas utilizar bases de datos (tanto SQL como NoSQL) sin tener que preocuparse por su administración física o de infraestructura. El proveedor de la nube se encarga de todo el "trabajo pesado": desde la configuración inicial, las actualizaciones de software y los parches de seguridad, hasta tareas críticas como la replicación de datos para alta disponibilidad y la creación de copias de seguridad automáticas.</p> <br>

            <h3>5. Función como servicio (FaaS)</h3>
            <p>Es un modelo de computación en la nube que permite a los desarrolladores ejecutar fragmentos de código o funciones en respuesta a eventos específicos sin tener que gestionar la infraestructura subyacente. En lugar de aprovisionar y mantener servidores, los desarrolladores simplemente escriben funciones que se ejecutan cuando se activan por eventos, como solicitudes HTTP, cambios en bases de datos o cargas de archivos.</p> <br>

            <h3>6. Redes como servicio (NaaS)</h3>
            <p>Las Redes como Servicio (NaaS) trasladan los conceptos de redes físicas (routers, switches, firewalls) a un entorno virtual y definido por software. Los usuarios pueden diseñar y controlar sus propias redes privadas y aisladas dentro de la nube (llamadas VPCs o VNETs), definir reglas de firewall, configurar balanceadores de carga para distribuir el tráfico web entre múltiples servidores y gestionar nombres de dominio (DNS) a escala global.</p>

        </div>
    </div>
    <hr> <br>
    <h2>Tecnologías clave para la computación en la nube</h2>
    <div class="component-section">
        <h3>Virtualizacion</h3>
        <div class="component-content-wrapper">
            <div class="component-text">
               <p>La virtualización es una tecnología que permite crear versiones virtuales de recursos físicos, como servidores, almacenamiento y redes. En el contexto de la computación en la nube, la virtualización es fundamental para optimizar el uso de los recursos y mejorar la flexibilidad y escalabilidad de los servicios ofrecidos. </p>
               <p>Al virtualizar los recursos, los proveedores de servicios en la nube pueden asignar dinámicamente capacidad según la demanda del usuario, lo que permite una utilización más eficiente y rentable de la infraestructura subyacente.</p>

            </div>
            <div class="component-image">
                <div class="image-container-width">
                    <img src="/src/img/modulos/cc/imagen3.jpg" alt="Servicio de virtualizacion" class="cc.img">
                    <br> 
                </div>      
            </div>
        </div>
    </div>
    <div class="component-section">
        <h3>Serverless Computing</h3>
        <div class="component-content-wrapper">
            <div class="component-text">
                <p>El cómputo sin servidor, o "serverless computing", es un modelo de computación en la nube donde los desarrolladores pueden construir y ejecutar aplicaciones sin tener que gestionar servidores. En este modelo, el proveedor de la nube se encarga automáticamente de la infraestructura, el escalado y la gestión de los servidores, permitiendo a los desarrolladores centrarse únicamente en escribir código y desarrollar funcionalidades.</p>
                <p>Con el cómputo sin servidor, los desarrolladores pueden implementar funciones o fragmentos de código que se ejecutan en respuesta a eventos específicos, como solicitudes HTTP, cambios en bases de datos o cargas de archivos. Este enfoque permite una mayor agilidad y eficiencia en el desarrollo de aplicaciones, ya que los desarrolladores no tienen que preocuparse por la administración de servidores ni por la capacidad de escalado.</p>
            </div>
            <div class="component-image">
                <div class="image-container-width">
                    <img src="/src/img/modulos/cc/imagen5.jpg" alt="Servicio de virtualizacion" class="cc.img">
                    <br> 
                </div>      
            </div>
        </div>
    </div>
    <div class="component-section">
        <h3>Contenedores</h3>
        <div class="component-content-wrapper">
            <div class="component-text">
                <p>Los contenedores son una tecnología de virtualización a nivel de sistema operativo que permite empaquetar una aplicación y sus dependencias en un entorno aislado y portátil. A diferencia de las máquinas virtuales tradicionales, que requieren un sistema operativo completo para cada instancia, los contenedores comparten el mismo núcleo del sistema operativo, lo que los hace más ligeros y eficientes en términos de recursos.</p>
                <p>En el contexto de la computación en la nube, los contenedores facilitan el desarrollo, despliegue y escalado de aplicaciones. Los desarrolladores pueden crear contenedores que incluyan todo lo necesario para ejecutar una aplicación, lo que garantiza que funcione de manera consistente en diferentes entornos, desde el desarrollo local hasta la producción en la nube. Además, los contenedores permiten una gestión más eficiente de los recursos y una mayor agilidad en la entrega de aplicaciones.</p>
            </div>
            <div class="component-image">
                <div class="image-container-width">
                    <img src="/src/img/modulos/cc/imagen4.png" alt="Servicio de virtualizacion" style="max-width: 90%; max-height: 90%;" class="cc.img">
                    <br> 
                </div>      
            </div>
        </div>
    </div>
    <hr> <br>
    <h2>Aplicaciones en la industria</h2>
    <div class="component-section">
        <div class="component-content-wrapper">
            <div class="component-text">
                <p>La computación en la nube tiene numerosas aplicaciones en la industria, incluyendo:</p>
                <ul>
                    <li><strong>Almacenamiento y respaldo de datos:</strong> Permite a las empresas almacenar grandes volúmenes de datos de manera segura y acceder a ellos desde cualquier lugar.</li> <br>
                    <li><strong>Análisis de datos:</strong> Facilita el procesamiento y análisis de grandes conjuntos de datos para obtener información valiosa y tomar decisiones informadas.</li> <br>
                    <li><strong>Desarrollo y despliegue de aplicaciones:</strong> Proporciona un entorno flexible para desarrollar, probar y desplegar aplicaciones de manera rápida y eficiente.</li> <br>
                    <li><strong>Colaboración y comunicación:</strong> Facilita el trabajo en equipo al permitir que múltiples usuarios accedan y trabajen en los mismos documentos y aplicaciones simultáneamente.</li> <br>
                    <li><strong>Escalabilidad:</strong> Permite a las empresas ajustar rápidamente sus recursos informáticos según la demanda, lo que es especialmente útil durante picos de actividad.</li> <br>
                </ul>
            </div>
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
