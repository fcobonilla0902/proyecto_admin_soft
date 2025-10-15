<?php
session_start();

require_once '../includes/database.php';

$identificador = mysqli_real_escape_string($db, $_SESSION['alumno']['identificador']);

$idJuego = mysqli_real_escape_string($db, $_GET['juego']);


// Buscar si el registro ya existe
$sql_check = "SELECT id FROM records_juegos WHERE identificador = '$identificador' AND juego = '$idJuego'";
$resultado_check = mysqli_query($db, $sql_check);

// Verificamos si la consulta de búsqueda tuvo éxito y si encontró alguna fila
if ($resultado_check && mysqli_num_rows($resultado_check) > 0) {
    $respuesta = ['success' => true, 'message' => 'El registro ya existía.'];

} else {
    // Si no existe, proceder con la inserción
    $sql_insert = "INSERT INTO records_juegos (identificador, juego) VALUES ('$identificador', '$idJuego')";
    $resultado_insert = mysqli_query($db, $sql_insert);

    // Verificar si la inserción fue exitosa
    if ($resultado_insert) {
        $respuesta = ['success' => true, 'message' => 'Registro guardado exitosamente.'];
    } else {
        $respuesta = ['success' => false, 'message' => 'Error al insertar en la base de datos: ' . mysqli_error($db)];
    }
}

//Cerrar conexión y enviar respuesta
mysqli_close($db);

header('Content-Type: application/json');
echo json_encode($respuesta);

?>