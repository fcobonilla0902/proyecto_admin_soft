<?php
include '../../includes/database.php'; // ajusta esta ruta según tu estructura
session_start();

header('Content-Type: application/json');

// ✅ Validar sesión
if (!isset($_SESSION['alumno']['identificador'])) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// ✅ Leer datos enviados desde JS
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!is_array($data) || !isset($data['porcentaje'])) {
    echo json_encode(['error' => 'Datos inválidos', 'recibido' => $data]);
    exit;
}

$porcentaje = (int)$data['porcentaje']; // convertir explícitamente a entero
$identificador = $_SESSION['alumno']['identificador'];
$modulo = 'Modulo 1'; // puedes hacerlo dinámico si quieres

// ✅ Verificar conexión
if (!$db) {
    echo json_encode(['error' => 'Sin conexión a base de datos']);
    exit;
}

$query = "INSERT INTO records_modulos (identificador, modulo, porcentaje)
          VALUES (?, ?, ?)
          ON DUPLICATE KEY UPDATE porcentaje = GREATEST(porcentaje, VALUES(porcentaje))";

$stmt = $db->prepare($query);
if (!$stmt) {
    echo json_encode(['error' => 'Error en prepare', 'detalle' => $db->error]);
    exit;
}

$stmt->bind_param("ssi", $identificador, $modulo, $porcentaje);

if ($stmt->execute()) {
    echo json_encode(['status' => 'ok', 'guardado' => $porcentaje]);
} else {
    echo json_encode(['error' => 'Error al ejecutar', 'detalle' => $stmt->error]);
}
