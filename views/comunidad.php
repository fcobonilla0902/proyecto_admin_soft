<?php 
include '../includes/database.php'; 
include '../includes/funciones.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$auth = isAuth();


$titulo = "Comunidad";
include_once '../templates/header.php'; 

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comentario = mysqli_real_escape_string($db, $_POST['contenido'] ?? '');

    $identificador = $_SESSION['alumno']['identificador']; 

    date_default_timezone_set('America/Monterrey');
    $ahora = new DateTime();
    $fecha = $ahora->format('Y-m-d H:i:s');

    if (!empty(trim($comentario))) {
        // La columna en la BD debe ser 'usuario_id' para que el JOIN funcione.
        $query = "INSERT INTO comunidad (identificador, comentario, fecha) VALUES ('$identificador', '$comentario', '$fecha')";
        $resultado_insert = mysqli_query($db, $query);

        if ($resultado_insert) {
            header("Location: /views/comunidad.php?op=4");
            exit;
        } else {
            $mensaje = "Hubo un error al guardar tu publicación. Inténtalo de nuevo.";
        }
    } 
}
?>
<link rel="stylesheet" href="../src/css/menu/comunidad.css">

<main class="contenedor">
    <div class="comunidad-container">
        <div class="comunidad-header">
            <h1>Comunidad: Comparte y Conecta</h1>
            <img src="/src/img/comunidad/comunidad.png" alt="Comunidad">
        </div>
        
        <div class="nuevo-comentario">
            <form method="POST" class="comentario-form">
                <label for="contenido">Deja tu comentario:</label>
                <div class="form-input-group">
                    <?php
                        if($auth == true){
                    ?>
                        <input type="text" id="contenido" name="contenido" placeholder="Escribe tu comentario..." maxlength="200" required>
                        <span id="contador">0 / 200</span>
                        <button type="submit" class="boton-publicar">Publicar</button>
                    <?php
                        } else {
                    ?>
                        <h3 class="no_login">Debes <a href="login.php">iniciar sesión</a> para publicar un comentario</h3>
                    <?php
                        }
                    ?>
                </div>
            </form>
        </div>

        <h2>Comentarios de Alumnos</h2>
        <div class="lista-comentarios">
            
            <?php
            $busqueda = "SELECT c.id, c.identificador, c.comentario, c.fecha, a.nombre, a.apellido, a.tipo,
                (SELECT COUNT(*) FROM reacciones WHERE id_comunidad = c.id AND reaccion = '1') AS likes,
                (SELECT COUNT(*) FROM reacciones WHERE id_comunidad = c.id AND reaccion = '0') AS dislikes
                FROM comunidad c
                JOIN alumnos a ON c.identificador = a.identificador
                ORDER BY c.fecha DESC;
            ";
            $resultadoBusqueda = mysqli_query($db, $busqueda);

            while ($comunidad = mysqli_fetch_assoc($resultadoBusqueda)):
                    $likes = isset($comunidad['likes']) ? (int)$comunidad['likes'] : 0;
                    $dislikes = isset($comunidad['dislikes']) ? (int)$comunidad['dislikes'] : 0;

                    $meses = [
                        1 => "enero", 2 => "febrero", 3 => "marzo", 4 => "abril",
                        5 => "mayo", 6 => "junio", 7 => "julio", 8 => "agosto",
                        9 => "septiembre", 10 => "octubre", 11 => "noviembre", 12 => "diciembre"
                    ];

                    $fecha = strtotime($comunidad['fecha']);
                    $dia = date("j", $fecha);
                    $mes = $meses[(int)date("n", $fecha)];
                    $anio = date("Y", $fecha);
                    $hora = date("g:i a", $fecha); // Ej: 10:35 pm

            ?>
            <div class="comentario">
                <div class="comentario-autor">
                    <div class="avatar">
                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="white"/>
                        </svg>
                    </div>
                    <div class="autor-info">
                        <p class="nombre"><?php echo htmlspecialchars($comunidad['nombre']); ?></p>
                        <p class="rol"><?php echo ($comunidad['tipo'] == 1) ? "Alumno de FIME" : "Alumno Externo"; ?></p> 
                    </div>
                </div>

                <div class="comentario-contenido">
                    <p><?php echo htmlspecialchars($comunidad['comentario']); ?></p>

                    <div class="comentario-footer">
                    <div class="comentario-fecha">
                        <p><?php echo $dia . " de " . $mes . " de " . $anio . ", " . $hora;?></p>
                    </div>

                    <div class="comentario-acciones">

                        <button type="button" class="btn-reaccion btn-like" data-tipo="1" data-id="<?php echo $comunidad['id']; ?>" aria-label="Me gusta">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0a4624" class="bi bi-hand-thumbs-up-fill" viewBox="0 0 16 16"> 
                                <path d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a10 10 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733q.086.18.138.363c.077.27.113.567.113.856s-.036.586-.113.856c-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.2 3.2 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16H8c-.605 0-1.07-.081-1.466-.218a4.8 4.8 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/> 
                            </svg> 
                            <span class="count-like"><?php echo $likes ?></span>
                        </button>

                        <button type="button" class="btn-reaccion btn-dislike" data-tipo="0" data-id="<?php echo $comunidad['id']; ?>" aria-label="No me gusta">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0a4624" style="cursor: pointer;" class="bi bi-hand-thumbs-down-fill" viewBox="0 0 16 16"> 
                                <path d="M6.956 14.534c.065.936.952 1.659 1.908 1.42l.261-.065a1.38 1.38 0 0 0 1.012-.965c.22-.816.533-2.512.062-4.51q.205.03.443.051c.713.065 1.669.071 2.516-.211.518-.173.994-.68 1.2-1.272a1.9 1.9 0 0 0-.234-1.734c.058-.118.103-.242.138-.362.077-.27.113-.568.113-.856 0-.29-.036-.586-.113-.857a2 2 0 0 0-.16-.403c.169-.387.107-.82-.003-1.149a3.2 3.2 0 0 0-.488-.9c.054-.153.076-.313.076-.465a1.86 1.86 0 0 0-.253-.912C13.1.757 12.437.28 11.5.28H8c-.605 0-1.07.08-1.466.217a4.8 4.8 0 0 0-.97.485l-.048.029c-.504.308-.999.61-2.068.723C2.682 1.815 2 2.434 2 3.279v4c0 .851.685 1.433 1.357 1.616.849.232 1.574.787 2.132 1.41.56.626.914 1.28 1.039 1.638.199.575.356 1.54.428 2.591"/>
                            </svg> 
                            <span class="count-dislike"><?php echo $dislikes; ?></span>
                        </button>
 
                    </div>
                    </div>
                </div>

            </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>

<script src="/src/js/menu/comunidad.js"></script>

<?php include_once '../templates/footer.php'; ?>