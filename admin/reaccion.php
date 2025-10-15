<?php
// reacion.php (AJAX JSON)
include '../includes/database.php';
include '../includes/funciones.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

isAuth();

header('Content-Type: application/json; charset=utf-8');

$identificador = $_SESSION['alumno']['identificador'] ?? null;
$id_comunidad = isset($_POST['id']) ? intval($_POST['id']) : null;
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;

$response = ['success' => false];

if ($identificador && $id_comunidad && ($tipo === "1" || $tipo === "0")) {
    // escape / sanitizar
    $identificador_esc = mysqli_real_escape_string($db, $identificador);
    $id_esc = intval($id_comunidad);
    $tipo_int = intval($tipo);

    $existeQ = "SELECT reaccion FROM reacciones WHERE identificador='$identificador_esc' AND id_comunidad='$id_esc'";
    $existe = mysqli_query($db, $existeQ);

    if ($existe && mysqli_num_rows($existe) > 0) {
        $row = mysqli_fetch_assoc($existe);
        if ((int)$row['reaccion'] === $tipo_int) {
            // eliminar
            $query = "DELETE FROM reacciones WHERE identificador='$identificador_esc' AND id_comunidad='$id_esc'";
        } else {
            // actualizar
            $query = "UPDATE reacciones SET reaccion='$tipo_int' WHERE identificador='$identificador_esc' AND id_comunidad='$id_esc'";
        }
    } else {
        // insertar
        $query = "INSERT INTO reacciones (identificador, id_comunidad, reaccion) VALUES ('$identificador_esc', '$id_esc', '$tipo_int')";
    }

    mysqli_query($db, $query);

    // obtener conteos actualizados
    $likesRes = mysqli_query($db, "SELECT COUNT(*) AS total FROM reacciones WHERE id_comunidad='$id_esc' AND reaccion='1'");
    $dislikesRes = mysqli_query($db, "SELECT COUNT(*) AS total FROM reacciones WHERE id_comunidad='$id_esc' AND reaccion='0'");

    $likes = 0;
    $dislikes = 0;
    if ($likesRes) $likes = (int)mysqli_fetch_assoc($likesRes)['total'];
    if ($dislikesRes) $dislikes = (int)mysqli_fetch_assoc($dislikesRes)['total'];

    $response = [
        'success' => true,
        'likes' => $likes,
        'dislikes' => $dislikes
    ];
}

echo json_encode($response);
exit;
