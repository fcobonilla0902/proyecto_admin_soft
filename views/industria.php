<?php
include '../includes/funciones.php';
include '../includes/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
isAuth();

$titulo = "Industria 4.0";

$identificador = $_SESSION['alumno']['identificador'] ?? null; 

$query = "SELECT * FROM modulos";
$resultado = mysqli_query($db, $query);

include_once '../templates/header.php'; ?>

<link rel="stylesheet" href="../src/css/menu/industria.css">


<main class="modulosWrapper">

    <?php
        while ($fila = mysqli_fetch_assoc($resultado)):
            $primerNombre = strtolower(explode(" ", $fila['nombre'])[0]);

            $mapaAcentos = [
                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                'ü' => 'u', 'ñ' => 'n', 'Á' => 'A', 'É' => 'E', 'Í' => 'I',
                'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ñ' => 'N',
            ];

            $primerNombreSinAcentos = strtr($primerNombre, $mapaAcentos);
            $modulo_id = $fila['id']; // ID del módulo actual
            $porcentaje = 0; // Porcentaje por defecto

            // ----------------------------------------------------
            // Lógica para obtener el porcentaje de completado
            // ----------------------------------------------------

            if ($identificador) {
                // Consulta para obtener el porcentaje de la tabla records_modulos para el usuario y módulo
                $query_porcentaje = "SELECT porcentaje FROM records_modulos WHERE identificador = '$identificador' AND modulo = $modulo_id";
                $resultado_porcentaje = mysqli_query($db, $query_porcentaje);

                if ($resultado_porcentaje && mysqli_num_rows($resultado_porcentaje) > 0) {
                    $record = mysqli_fetch_assoc($resultado_porcentaje);
                    $porcentaje = $record['porcentaje']; // Asignar el porcentaje encontrado
                }
                
                // Liberar el resultado de la consulta de porcentaje
                if ($resultado_porcentaje) {
                    mysqli_free_result($resultado_porcentaje);
                }
            }

            // Determinar el texto del botón
            $texto_accion = ($porcentaje > 0 && $porcentaje < 100) ? 'Continuar' : (($porcentaje == 100) ? 'Completado' : 'Comenzar');

    ?>
        <a href="modulos/<?php echo $primerNombreSinAcentos;?>.php?op=2">
            <div class="modulo">
                <h1><?php echo "Módulo " . $fila['id']; ?></h1>
                <h3><?php echo $fila['nombre'];?></h3>
                <img src="<?php echo '/src/img/modulos/' . $primerNombreSinAcentos . '.png';?>" alt="<?php echo $fila['nombre'];?>">

                <div class="info__porcentaje">
                    <div class="barra-progreso" style="width: <?php echo $porcentaje; ?>%;"></div>
                    <h4 class="porcentaje-texto"><?php echo $porcentaje; ?>% Completado</h4>
                </div>
                
                <div class="info__texto"><h4><?php echo $texto_accion; ?></h4></div> 
            </div>
        </a> 
    <?php
        endWhile;
        
        // Liberar el resultado de la consulta principal
        if ($resultado) {
            mysqli_free_result($resultado);
        }
    ?>

</main>

 <?php include_once '../templates/footer.php'; ?>